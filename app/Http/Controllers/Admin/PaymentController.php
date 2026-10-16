<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController as PublicPaymentController;
use App\Models\Payment;
use App\Services\SwishGateway;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = $this->filtered($request)->latest('id')->paginate(25)->withQueryString();

        $stats = [
            'confirmed_total' => Payment::where('status', Payment::CONFIRMED)->sum('amount'),
            'confirmed'       => Payment::where('status', Payment::CONFIRMED)->count(),
            'pending'         => Payment::where('status', Payment::PENDING)->count(),
            'rejected'        => Payment::where('status', Payment::REJECTED)->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load('logs');
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Manually confirm a payment after verifying it on the Swish portal / mobile money statement.
     */
    public function confirm(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'provider_transaction_id' => 'required|string|max:100',
            'notes'                   => 'nullable|string|max:1000',
        ]);

        $payment->markConfirmed($data['provider_transaction_id'], 'admin: ' . $request->user()->name, $data);
        $payment->update(['notes' => $data['notes'] ?? $payment->notes]);

        return back()->with('success', 'Payment marked as confirmed.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $data = $request->validate(['notes' => 'nullable|string|max:1000']);

        $payment->markRejected('admin: ' . $request->user()->name, $data);
        $payment->update(['notes' => $data['notes'] ?? $payment->notes]);

        return back()->with('success', 'Payment marked as rejected.');
    }

    public function check(Payment $payment, SwishGateway $swish)
    {
        if (blank($payment->swish_trans_link_id)) {
            return back()->with('error', 'This payment has no Swish transaction link ID to check.');
        }

        $status = PublicPaymentController::checkWithSwish($swish, $payment);

        return back()->with($status === 'pending' ? 'error' : 'success', match ($status) {
            'success' => 'Swish confirmed the payment.',
            'failed'  => 'Swish reports the payment failed.',
            default   => 'Swish did not return a final status. See the log below for its response.',
        });
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filtered($request)->latest('id');

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Reference', 'Transaction ID', 'Status', 'Type', 'Purpose', 'Payer', 'Phone', 'Amount (ZMW)', 'Method', 'Initiated', 'Paid at', 'Confirmed via', 'Swish link ID', 'Notes']);
            $query->chunk(500, function ($payments) use ($out) {
                foreach ($payments as $p) {
                    fputcsv($out, [
                        $p->transaction_reference, $p->provider_transaction_id, $p->status, $p->category, $p->description,
                        $p->payer_name, $p->phone_number, $p->amount, $p->methodLabel(), $p->created_at,
                        $p->paid_at, $p->confirmed_via, $p->swish_trans_link_id, $p->notes,
                    ]);
                }
            });
            fclose($out);
        }, 'payments-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }

    private function filtered(Request $request): Builder
    {
        return Payment::query()
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->input('category'), fn ($q, $category) => $q->where('category', $category))
            ->when($request->input('from'), fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($request->input('to'), fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->when(trim((string) $request->input('q')), function ($q, $term) {
                $q->where(fn ($q) => $q
                    ->where('transaction_reference', 'like', "%{$term}%")
                    ->orWhere('provider_transaction_id', 'like', "%{$term}%")
                    ->orWhere('swish_trans_link_id', 'like', "%{$term}%")
                    ->orWhere('phone_number', 'like', "%{$term}%")
                    ->orWhere('payer_name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%"));
            });
    }
}
