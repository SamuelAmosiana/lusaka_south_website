<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\SwishGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(private SwishGateway $swish)
    {
    }

    /**
     * Create a pending payment and start it with Swish.
     */
    public function initiate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'method'   => ['required', Rule::in(SwishGateway::methods())],
            'category' => ['required', Rule::in(['Payment', 'Donation'])],
            'purpose'  => 'required|string|max:150',
            'name'     => 'nullable|string|max:150',
            'amount'   => 'required|numeric|min:1|max:1000000',
            'phone'    => 'required|string|max:20',
        ]);

        $phone = SwishGateway::normalisePhone($data['phone']);
        if ($data['method'] !== 'card' && ! SwishGateway::isZambianNumber($phone)) {
            return $this->error('Mobile money needs a Zambian MTN, Airtel or Zamtel number (e.g. 0971234567). For other numbers, please pay by card.', 422);
        }
        if (! $this->swish->isConfigured()) {
            Log::error('Swish payment attempted but SWISH_* credentials are not set.');
            return $this->error('Online payments are temporarily unavailable. Please use the bank transfer details or contact the Finance Office.', 503);
        }

        $payment = Payment::create([
            'transaction_reference' => Payment::newReference(),
            'payer_name'            => $data['name'] ?? null,
            'phone_number'          => $phone,
            'amount'                => $data['amount'],
            'status'                => Payment::PENDING,
            'category'              => $data['category'],
            'description'           => $data['purpose'],
            'swish_method'          => $data['method'],
        ]);

        try {
            $result = $this->swish->initiate($data['method'], $phone, (float) $data['amount'], "{$data['category']}: {$data['purpose']}");
        } catch (RuntimeException $e) {
            $payment->update(['status' => Payment::REJECTED]);
            $payment->log('initiate_failed', $e->getMessage());
            return $this->error($e->getMessage(), 502);
        }

        $payment->update(['swish_trans_link_id' => (string) ($result['transLinkID'] ?? '')]);
        $payment->log('initiated', $result);

        return response()->json([
            'success'    => true,
            'reference'  => $payment->transaction_reference,
            'method'     => $data['method'],
            'phone'      => $phone,
            'message'    => $result['message'] ?? 'Payment initiated',
            'qrImage'    => $result['imageInBase64'] ?? null,
            'paymentUrl' => $result['paymentUrl'] ?? null,
        ]);
    }

    /**
     * Polled by the payment modal until the payment is confirmed or rejected.
     */
    public function status(Payment $payment): JsonResponse
    {
        // The Swish callback updates the record, so the database is checked on every poll;
        // Swish itself is asked at most every 15 seconds per payment.
        if ($payment->isPending() && filled($payment->swish_trans_link_id)
            && Cache::add("swish-status-check:{$payment->id}", true, 15)) {
            self::checkWithSwish($this->swish, $payment);
        }

        return $this->statusResponse($payment->fresh());
    }

    /**
     * Ask Swish for the payment's outcome and record it. Shared with the admin "Check with Swish" action.
     */
    public static function checkWithSwish(SwishGateway $swish, Payment $payment): string
    {
        $result = $swish->status($payment->swish_trans_link_id, $payment->phone_number);
        $payment->log('status_check', ['http' => $result['http'], 'response' => $result['raw']]);

        if ($result['status'] === 'success') {
            $payment->markConfirmed($result['transaction_id'], 'status_check', $result['raw']);
        } elseif ($result['status'] === 'failed') {
            $payment->markRejected('status_check', $result['raw']);
        }

        return $result['status'];
    }

    /**
     * Swish posts here after a payment succeeds or fails.
     */
    public function callback(Request $request): JsonResponse
    {
        $data = $request->json()->all() ?: $request->all();
        Log::info('Swish callback received', $data);

        $linkId  = SwishGateway::linkIdFrom($data);
        $result  = SwishGateway::parseResult($data);
        $payment = $this->findCallbackPayment($linkId, $data);

        if (! $payment) {
            Log::warning('Swish callback did not match any payment', $data);
            // Acknowledge so Swish doesn't keep retrying; the payload is in the log for reconciliation.
            return response()->json(['success' => true, 'message' => 'Acknowledged (no matching payment)']);
        }

        $payment->log('callback_received', $data);

        // Don't record the Swish link id as the network's transaction id
        $transactionId = $result['transaction_id'] !== $payment->swish_trans_link_id ? $result['transaction_id'] : null;

        if ($result['status'] === 'success' && $payment->status !== Payment::CONFIRMED) {
            $payment->markConfirmed($transactionId, 'callback', $data);
        } elseif ($result['status'] === 'failed' && $payment->isPending()) {
            $payment->markRejected('callback', $data);
        }

        return response()->json([
            'success'               => true,
            'transaction_reference' => $payment->transaction_reference,
            'status'                => $payment->status,
        ]);
    }

    private function findCallbackPayment(?string $linkId, array $data): ?Payment
    {
        if ($linkId) {
            return Payment::where('swish_trans_link_id', $linkId)->first()
                ?? Payment::where('transaction_reference', $linkId)->first();
        }

        // Some Swish versions send the link id as transactionId
        $flat = array_change_key_case($data);
        $candidate = $flat['transactionid'] ?? null;

        return $candidate ? Payment::where('swish_trans_link_id', (string) $candidate)->first() : null;
    }

    private function statusResponse(Payment $payment): JsonResponse
    {
        return response()->json([
            'success'        => true,
            'payment_status' => match ($payment->status) {
                Payment::CONFIRMED => 'success',
                Payment::REJECTED  => 'failed',
                default            => 'pending',
            },
            'reference'      => $payment->transaction_reference,
            'transaction_id' => $payment->provider_transaction_id,
            'amount'         => number_format((float) $payment->amount, 2),
            'paid_at'        => $payment->paid_at?->format('d M Y, H:i'),
        ]);
    }

    private function error(string $message, int $code): JsonResponse
    {
        return response()->json(['success' => false, 'error' => $message], $code);
    }
}
