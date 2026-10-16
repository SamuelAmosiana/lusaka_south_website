<?php

namespace Tests\Feature;

use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SwishPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.swish.access_key'    => 'test-access',
            'services.swish.security_key'  => 'test-secret',
            'services.swish.till_code'     => '123',
            'services.swish.merchant_code' => '456',
            'services.swish.return_url'    => 'https://example.test/callback',
        ]);
        URL::forceRootUrl('http://localhost'); // .env APP_URL carries a /lsuc_website/public subpath
        Http::preventStrayRequests();
    }

    private function pay(array $overrides = [])
    {
        return $this->postJson(route('pay.initiate'), array_merge([
            'method' => 'ussd', 'category' => 'Payment', 'purpose' => 'Tuition',
            'amount' => 150, 'phone' => '0971234567',
        ], $overrides));
    }

    public function test_pages_render_with_payment_buttons(): void
    {
        $this->get(route('pay.online'))->assertOk()->assertSee('data-swish-open="card"', false)->assertSee('data-swish-open="mobile"', false);
        $this->get(route('pay.donate'))->assertOk()->assertSee('Donate with Card')->assertSee('Donate via Mobile Money');
    }

    public function test_ussd_payment_is_initiated_and_recorded(): void
    {
        Http::fake(['*/sendUSSDNotification' => Http::response(['responseCode' => '200', 'transLinkID' => 'TL1', 'message' => 'Sent'])]);

        $this->pay()->assertOk()->assertJson(['success' => true, 'method' => 'ussd', 'phone' => '260971234567']);

        $payment = Payment::sole();
        $this->assertSame('pending', $payment->status);
        $this->assertSame('TL1', $payment->swish_trans_link_id);
        Http::assertSent(fn ($r) => $r['subscriberNumber'] === '260971234567'
            && $r['tillCode'] === '123' && $r->hasHeader('accessKey', 'test-access') && $r->hasHeader('password', 'test-secret'));
    }

    public function test_card_payment_returns_checkout_url_and_accepts_foreign_number(): void
    {
        Http::fake(['*/initiateCardPayment' => Http::response(['responseCode' => '200', 'transLinkID' => 'TL2', 'paymentUrl' => 'https://pay.test/checkout'])]);

        $this->pay(['method' => 'card', 'phone' => '+267 71 234 567'])->assertOk()->assertJson(['paymentUrl' => 'https://pay.test/checkout']);
    }

    public function test_mobile_money_rejects_non_zambian_number(): void
    {
        $this->pay(['phone' => '+267 71 234 567'])->assertStatus(422);
        $this->assertDatabaseCount('payments', 0);
    }

    public function test_gateway_rejection_is_reported(): void
    {
        Http::fake(['*' => Http::response(['responseCode' => '400', 'message' => 'Invalid till'])]);

        $this->pay()->assertStatus(502)->assertJson(['error' => 'Invalid till']);
        $this->assertSame('rejected', Payment::sole()->status);
    }

    public function test_unconfigured_gateway_fails_gracefully(): void
    {
        config(['services.swish.access_key' => null]);
        $this->pay()->assertStatus(503);
    }

    private function pendingPayment(string $ref = 'LSUCREF1', string $linkId = 'TL3'): Payment
    {
        return Payment::create(['transaction_reference' => $ref, 'phone_number' => '260971234567', 'amount' => 10, 'swish_trans_link_id' => $linkId, 'swish_method' => 'ussd']);
    }

    public function test_status_returns_pending_while_swish_status_api_errors(): void
    {
        $this->pendingPayment();
        Http::fake(['*/getTransactionStatus' => Http::response(['message' => 'An error has occurred.'], 500)]);

        $this->getJson(route('pay.status', 'LSUCREF1'))->assertJson(['payment_status' => 'pending']);
        $this->assertDatabaseHas('payment_logs', ['transaction_reference' => 'LSUCREF1', 'action_taken' => 'status_check']);
    }

    public function test_swish_is_asked_at_most_every_15_seconds(): void
    {
        $this->pendingPayment();
        Http::fake(['*/getTransactionStatus' => Http::response(['message' => 'An error has occurred.'], 500)]);

        $this->getJson(route('pay.status', 'LSUCREF1'));
        $this->getJson(route('pay.status', 'LSUCREF1'));
        Http::assertSentCount(1);
    }

    public function test_status_check_confirms_payment_with_transaction_id(): void
    {
        $payment = $this->pendingPayment();
        Http::fake(['*/getTransactionStatus' => Http::response(['responseCode' => '200', 'data' => ['status' => 'Success', 'transid' => 'MP123']])]);

        $this->getJson(route('pay.status', 'LSUCREF1'))
            ->assertJson(['payment_status' => 'success', 'transaction_id' => 'MP123', 'amount' => '10.00']);
        $this->assertSame('status_check', $payment->fresh()->confirmed_via);
    }

    public function test_callback_confirms_payment_and_poll_shows_receipt(): void
    {
        $payment = $this->pendingPayment('LSUCREF2', '5038806682992260686');
        Http::fake();

        // Payload shape documented for the Swish callback
        $this->postJson(route('pay.swish.callback'), [
            'txnLinkID' => '5038806682992260686', 'transid' => '5089143', 'amount' => '10',
            'status' => 'success', 'transactionDate' => '13/10/2026 12:33:07', 'subscriberNumber' => '260971234567',
        ])->assertOk()->assertJson(['status' => 'confirmed']);

        $payment->refresh();
        $this->assertSame('confirmed', $payment->status);
        $this->assertSame('5089143', $payment->provider_transaction_id);
        $this->assertNotNull($payment->paid_at);

        $this->getJson(route('pay.status', 'LSUCREF2'))->assertJson(['payment_status' => 'success', 'transaction_id' => '5089143']);
        Http::assertNothingSent();
    }

    public function test_callback_marks_payment_failed_without_csrf_token(): void
    {
        $payment = $this->pendingPayment('LSUCREF3', 'TL4');

        $this->post(route('pay.swish.callback'), ['txnLinkID' => 'TL4', 'status' => 'failed'])->assertOk();
        $this->assertSame('rejected', $payment->fresh()->status);
    }

    public function test_unmatched_callback_is_acknowledged(): void
    {
        $this->postJson(route('pay.swish.callback'), ['txnLinkID' => 'UNKNOWN', 'status' => 'success'])->assertOk();
    }

    public function test_admin_can_list_view_confirm_and_export_payments(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);
        $payment = $this->pendingPayment();

        $this->actingAs($admin)->get(route('admin.payments.index'))->assertOk()->assertSee('LSUCREF1');
        $this->actingAs($admin)->get(route('admin.payments.index', ['status' => 'confirmed']))->assertOk()->assertDontSee('LSUCREF1');
        $this->actingAs($admin)->get(route('admin.payments.show', $payment))->assertOk()->assertSee('Mark as confirmed');

        $this->actingAs($admin)->post(route('admin.payments.confirm', $payment), ['provider_transaction_id' => 'MP999', 'notes' => 'Checked portal'])->assertRedirect();
        $payment->refresh();
        $this->assertSame(['confirmed', 'MP999'], [$payment->status, $payment->provider_transaction_id]);
        $this->assertStringStartsWith('admin:', $payment->confirmed_via);

        $csv = $this->actingAs($admin)->get(route('admin.payments.export'))->assertOk()->streamedContent();
        $this->assertStringContainsString('MP999', $csv);
    }

    public function test_payments_admin_requires_admin(): void
    {
        $this->get(route('admin.payments.index'))->assertRedirect(route('login'));
    }
}
