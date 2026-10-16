@extends('layouts.admin')
@section('title','Payments')
@section('page-title','Online Payments')
@section('content')
@php
    $badge = fn($status) => match($status) { 'confirmed' => 'badge-green', 'rejected' => 'badge-red', default => 'badge-orange' };
@endphp
<div class="stat-cards">
    <div class="stat-card" style="border-left-color:var(--primary-green)"><i class="fa fa-sack-dollar" style="color:var(--primary-green)"></i><h2>ZMW {{ number_format($stats['confirmed_total'], 2) }}</h2><p>Total received</p></div>
    <div class="stat-card" style="border-left-color:var(--primary-green)"><i class="fa fa-circle-check" style="color:var(--primary-green)"></i><h2>{{ $stats['confirmed'] }}</h2><p>Confirmed</p></div>
    <div class="stat-card"><i class="fa fa-hourglass-half"></i><h2>{{ $stats['pending'] }}</h2><p>Pending</p></div>
    <div class="stat-card" style="border-left-color:#dc3545"><i class="fa fa-circle-xmark" style="color:#dc3545"></i><h2>{{ $stats['rejected'] }}</h2><p>Rejected / failed</p></div>
</div>

<div class="card">
    <div class="card-header" style="flex-wrap:wrap;gap:12px">
        <h3><i class="fa fa-money-bill-wave me-2"></i>Payments ({{ $payments->total() }})</h3>
        <a href="{{ route('admin.payments.export', request()->query()) }}" class="btn btn-outline btn-sm"><i class="fa fa-file-csv"></i>Export CSV</a>
    </div>

    <form method="GET" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;gap:10px;margin-bottom:20px;align-items:end" class="payments-filter">
        <div><label style="font-size:.8rem;font-weight:600">Search</label><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Reference, transaction ID, phone, name…"></div>
        <div><label style="font-size:.8rem;font-weight:600">Status</label>
            <select name="status" class="form-control">
                <option value="">All</option>
                @foreach(['confirmed'=>'Confirmed','pending'=>'Pending','rejected'=>'Rejected'] as $v => $l)<option value="{{ $v }}" @selected(request('status')===$v)>{{ $l }}</option>@endforeach
            </select>
        </div>
        <div><label style="font-size:.8rem;font-weight:600">Type</label>
            <select name="category" class="form-control">
                <option value="">All</option>
                @foreach(['Payment','Donation'] as $c)<option @selected(request('category')===$c)>{{ $c }}</option>@endforeach
            </select>
        </div>
        <div><label style="font-size:.8rem;font-weight:600">From</label><input type="date" name="from" value="{{ request('from') }}" class="form-control"></div>
        <div><label style="font-size:.8rem;font-weight:600">To</label><input type="date" name="to" value="{{ request('to') }}" class="form-control"></div>
        <div style="display:flex;gap:6px"><button class="btn btn-primary"><i class="fa fa-filter"></i>Filter</button>@if(request()->query())<a href="{{ route('admin.payments.index') }}" class="btn btn-outline" title="Clear"><i class="fa fa-xmark"></i></a>@endif</div>
    </form>

    <div style="overflow-x:auto">
    <table>
        <thead><tr><th>Date</th><th>Reference / Transaction ID</th><th>Payer</th><th>Purpose</th><th>Amount</th><th>Method</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse($payments as $p)
            <tr>
                <td style="white-space:nowrap">{{ $p->created_at->format('d M Y') }}<br><small style="color:var(--gray-600)">{{ $p->created_at->format('H:i') }}</small></td>
                <td><code style="font-size:.82rem">{{ $p->transaction_reference }}</code>
                    <br><small style="color:var(--gray-600)">{{ $p->provider_transaction_id ? 'Txn: '.$p->provider_transaction_id : '—' }}</small></td>
                <td>{{ $p->payer_name ?: '—' }}<br><small style="color:var(--gray-600)">{{ $p->phone_number }}</small></td>
                <td><span class="badge badge-blue">{{ $p->category }}</span><br><small>{{ Str::limit($p->description, 40) }}</small></td>
                <td style="white-space:nowrap"><strong>ZMW {{ number_format($p->amount, 2) }}</strong></td>
                <td><small>{{ $p->methodLabel() }}</small></td>
                <td><span class="badge {{ $badge($p->status) }}">{{ ucfirst($p->status) }}</span></td>
                <td><a href="{{ route('admin.payments.show', $p) }}" class="btn btn-primary btn-sm" title="View details"><i class="fa fa-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--gray-600)">No payments found.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div style="margin-top:20px">{{ $payments->links() }}</div>
</div>
<style>@media(max-width:1100px){.payments-filter{grid-template-columns:1fr 1fr!important}}</style>
@endsection
