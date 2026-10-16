@extends('layouts.admin')
@section('title','Payment '.$payment->transaction_reference)
@section('page-title','Payment Details')
@section('content')
@php
    $badge = match($payment->status) { 'confirmed' => 'badge-green', 'rejected' => 'badge-red', default => 'badge-orange' };
    $rows = [
        'Reference'            => $payment->transaction_reference,
        'Transaction ID'       => $payment->provider_transaction_id ?: '—',
        'Amount'               => 'ZMW '.number_format($payment->amount, 2),
        'Type'                 => $payment->category,
        'Purpose'              => $payment->description,
        'Payer'                => $payment->payer_name ?: '—',
        'Phone'                => $payment->phone_number,
        'Method'               => $payment->methodLabel(),
        'Initiated'            => $payment->created_at->format('d M Y, H:i:s'),
        'Paid at'              => $payment->paid_at?->format('d M Y, H:i:s') ?? '—',
        'Confirmed / updated via' => $payment->confirmed_via ?: '—',
        'Swish link ID'        => $payment->swish_trans_link_id ?: '—',
        'Notes'                => $payment->notes ?: '—',
    ];
@endphp
<div style="margin-bottom:15px"><a href="{{ route('admin.payments.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i>All payments</a></div>

<div style="display:grid;grid-template-columns:minmax(0,3fr) minmax(0,2fr);gap:20px" class="payment-grid">
    <div class="card">
        <div class="card-header">
            <h3><i class="fa fa-receipt me-2"></i>{{ $payment->transaction_reference }}</h3>
            <span class="badge {{ $badge }}" style="font-size:.9rem">{{ ucfirst($payment->status) }}</span>
        </div>
        <table>
            @foreach($rows as $label => $value)
                <tr><th style="width:40%;text-transform:none;letter-spacing:0">{{ $label }}</th><td style="word-break:break-all">{{ $value }}</td></tr>
            @endforeach
        </table>
    </div>

    <div>
        @if($payment->swish_trans_link_id)
        <div class="card">
            <div class="card-header"><h3><i class="fa fa-arrows-rotate me-2"></i>Check with Swish</h3></div>
            <p style="color:var(--gray-600);font-size:.9rem;margin-bottom:12px">Ask Swish for this payment's current status.</p>
            <form method="POST" action="{{ route('admin.payments.check', $payment) }}">@csrf<button class="btn btn-primary btn-sm"><i class="fa fa-arrows-rotate"></i>Check status now</button></form>
        </div>
        @endif

        @if($payment->status !== 'confirmed')
        <div class="card">
            <div class="card-header"><h3><i class="fa fa-circle-check me-2"></i>Confirm manually</h3></div>
            <p style="color:var(--gray-600);font-size:.9rem;margin-bottom:12px">Use this after verifying the payment on the Swish merchant portal or your mobile money statement.</p>
            <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}" onsubmit="return confirm('Mark this payment as confirmed?')">
                @csrf
                <div class="form-group"><label>Transaction ID *</label><input name="provider_transaction_id" class="form-control" required maxlength="100" value="{{ old('provider_transaction_id', $payment->provider_transaction_id) }}" placeholder="From the Swish portal / SMS"></div>
                <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="2" maxlength="1000" placeholder="e.g. Verified on Swish portal by Finance"></textarea></div>
                @error('provider_transaction_id')<div class="alert-error">{{ $message }}</div>@enderror
                <button class="btn btn-orange btn-sm"><i class="fa fa-check"></i>Mark as confirmed</button>
            </form>
        </div>
        @endif

        @if($payment->status === 'pending')
        <div class="card">
            <div class="card-header"><h3><i class="fa fa-ban me-2"></i>Reject</h3></div>
            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" onsubmit="return confirm('Mark this payment as rejected?')">
                @csrf
                <div class="form-group"><label>Reason</label><textarea name="notes" class="form-control" rows="2" maxlength="1000" placeholder="e.g. Payer cancelled, no funds received"></textarea></div>
                <button class="btn btn-danger btn-sm"><i class="fa fa-ban"></i>Mark as rejected</button>
            </form>
        </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header"><h3><i class="fa fa-list me-2"></i>Gateway log ({{ $payment->logs->count() }})</h3></div>
    <div style="overflow-x:auto">
    <table>
        <thead><tr><th>Time</th><th>Event</th><th>Data</th></tr></thead>
        <tbody>
        @forelse($payment->logs as $log)
            <tr>
                <td style="white-space:nowrap">{{ $log->created_at->format('d M H:i:s') }}</td>
                <td><span class="badge badge-blue">{{ str_replace('_',' ',$log->action_taken) }}</span></td>
                <td><code style="font-size:.78rem;white-space:pre-wrap;word-break:break-all">{{ Str::limit($log->raw_response, 600) }}</code></td>
            </tr>
        @empty
            <tr><td colspan="3" style="text-align:center;color:var(--gray-600)">No log entries.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
<style>@media(max-width:1000px){.payment-grid{grid-template-columns:1fr!important}}</style>
@endsection
