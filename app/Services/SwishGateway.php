<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Netone Swish payment gateway (USSD, push notification, QR code and card checkout).
 * Ported from the legacy api/swish_initiate.php and api/swish_status.php.
 */
class SwishGateway
{
    private const INITIATE_ENDPOINTS = [
        'ussd' => '/v2/api/web/sendUSSDNotification',
        'push' => '/v2/api/web/initiateprompt',
        'qr'   => '/v2/api/web/paymentInstance',
        'card' => '/v2/api/web/initiateCardPayment',
    ];

    private const STATUS_ENDPOINT = '/v2/api/web/getTransactionStatus';

    public const SUCCESS_STATUSES = ['completed', 'success', 'successful', 'paid', 'approved', 'confirmed'];
    public const FAILED_STATUSES  = ['failed', 'failure', 'cancelled', 'canceled', 'rejected', 'declined', 'reversed', 'expired'];

    public static function methods(): array
    {
        return array_keys(self::INITIATE_ENDPOINTS);
    }

    public function isConfigured(): bool
    {
        return filled(config('services.swish.access_key'))
            && filled(config('services.swish.security_key'))
            && filled(config('services.swish.till_code'));
    }

    /**
     * Start a payment. Returns the decoded Swish response (transLinkID, imageInBase64, paymentUrl, ...).
     *
     * @throws RuntimeException when Swish is unreachable or rejects the request
     */
    public function initiate(string $method, string $phone, float $amount, string $remark): array
    {
        $payload = [
            'subscriberNumber' => $phone,
            'tillCode'         => config('services.swish.till_code'),
            'totalAmount'      => (string) $amount,
            'returnUrl'        => config('services.swish.return_url'),
        ];
        if ($method === 'ussd') {
            $payload += ['mpin' => '', 'remark1' => $remark];
        }

        try {
            $response = $this->client()->post(self::INITIATE_ENDPOINTS[$method], $payload);
        } catch (ConnectionException $e) {
            throw new RuntimeException('Could not reach the payment gateway. Please try again.', 0, $e);
        }

        $data = $response->json();
        if (! is_array($data)) {
            throw new RuntimeException('Unexpected response from the payment gateway.');
        }
        if ((string) ($data['responseCode'] ?? '') !== '200') {
            throw new RuntimeException($data['message'] ?? 'The payment gateway declined the request.');
        }

        return $data;
    }

    /**
     * Ask Swish for a transaction's status.
     * Returns ['status' => success|failed|pending, 'transaction_id' => ?string, 'http' => int, 'raw' => mixed].
     *
     * Note: as of Oct 2026 this endpoint answers HTTP 500 for this merchant account whatever the payload,
     * so confirmation in practice comes from the callback. The check stays as a backup and is logged.
     */
    public function status(string $transLinkId, string $phone): array
    {
        try {
            $response = $this->client()->timeout(15)->post(self::STATUS_ENDPOINT, [
                'transactionId'    => $transLinkId,
                'merchantCode'     => config('services.swish.merchant_code'),
                'accountNo'        => $phone,
                'subscriberNumber' => $phone,
                'tillCode'         => config('services.swish.till_code'),
            ]);
        } catch (ConnectionException $e) {
            return ['status' => 'pending', 'transaction_id' => null, 'http' => 0, 'raw' => $e->getMessage()];
        }

        $data = $response->json() ?? $response->body();
        $result = $response->successful() && is_array($data)
            ? self::parseResult($data)
            : ['status' => 'pending', 'transaction_id' => null];

        return $result + ['http' => $response->status(), 'raw' => $data];
    }

    /**
     * Read the outcome from a Swish status response or callback payload.
     * Field names vary between Swish API versions, so every known variant is checked.
     */
    public static function parseResult(array $data): array
    {
        $flat = array_change_key_case($data + (is_array($data['data'] ?? null) ? $data['data'] : []));

        $status = 'pending';
        foreach (['status', 'transstatus', 'paymentstatus', 'transactionstatus'] as $key) {
            if (isset($flat[$key]) && is_scalar($flat[$key]) && ($s = self::normaliseStatus((string) $flat[$key])) !== 'pending') {
                $status = $s;
                break;
            }
        }

        $transactionId = null;
        foreach (['transid', 'transactionid', 'txnid', 'receiptnumber', 'externalid', 'operatortransactionid'] as $key) {
            if (filled($flat[$key] ?? null) && is_scalar($flat[$key])) {
                $transactionId = (string) $flat[$key];
                break;
            }
        }

        return ['status' => $status, 'transaction_id' => $transactionId];
    }

    /**
     * The Swish transLinkID a callback refers to.
     */
    public static function linkIdFrom(array $data): ?string
    {
        $flat = array_change_key_case($data);
        foreach (['txnlinkid', 'translinkid', 'transactionlinkid'] as $key) {
            if (filled($flat[$key] ?? null)) {
                return (string) $flat[$key];
            }
        }

        return null;
    }

    public static function normaliseStatus(string $status): string
    {
        $status = strtolower(trim($status));

        return match (true) {
            in_array($status, self::SUCCESS_STATUSES, true) => 'success',
            in_array($status, self::FAILED_STATUSES, true)  => 'failed',
            default                                          => 'pending',
        };
    }

    /**
     * Normalise a phone number to the 260xxxxxxxxx format Swish expects.
     * Non-Zambian numbers are returned cleaned but otherwise unchanged (card payments only).
     */
    public static function normalisePhone(string $raw): string
    {
        $cleaned = preg_replace('/[\s\-.()]/', '', $raw);
        $digits  = ltrim($cleaned, '+');

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        return match (true) {
            (bool) preg_match('/^260\d{9}$/', $digits)       => $digits,
            (bool) preg_match('/^0\d{9}$/', $digits)         => '260' . substr($digits, 1),
            (bool) preg_match('/^(9[5-7]|7[5-7])\d{7}$/', $digits) => '260' . $digits,
            default                                          => $cleaned,
        };
    }

    public static function isZambianNumber(string $phone): bool
    {
        return (bool) preg_match('/^260\d{9}$/', $phone);
    }

    private function client()
    {
        return Http::baseUrl(config('services.swish.base_url'))
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'accessKey' => config('services.swish.access_key'),
                'password'  => config('services.swish.security_key'),
            ])
            ->withOptions(['verify' => config('services.swish.verify_ssl')])
            ->timeout(30);
    }
}
