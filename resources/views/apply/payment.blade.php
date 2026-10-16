@extends('layouts.app')
@section('title','Payment Instructions - '.config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>Payment Instructions</h1><p>How to pay your application fee and tuition</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; <a href="{{ route('pay.online') }}">Pay Online</a> &rsaquo; Payment Instructions</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Payment Methods</h2><p>We accept the following payment methods for fees and applications.</p></div>
    <div class="cards-grid">
        <div class="card" id="bank-deposit" style="scroll-margin-top:100px"><div class="card-icon"><i class="fa fa-building-columns"></i></div>
            <h3>Bank Deposit</h3>
            <p><strong>Bank:</strong> Zanaco Bank<br><strong>Account Name:</strong> Lusaka South University College<br><strong>Account No:</strong> [Contact Finance Office]<br><strong>Branch:</strong> Lusaka</p>
            <p style="margin-top:10px;color:var(--primary-orange);font-size:.9rem"><i class="fa fa-circle-info me-1"></i>Bring your deposit slip to the Finance Office for processing.</p>
        </div>
        <div class="card" id="mobile-money" style="scroll-margin-top:100px"><div class="card-icon"><i class="fa fa-mobile-screen-button"></i></div>
            <h3>Mobile Money</h3>
            <p><strong>MTN Mobile Money:</strong> +260 770359518<br><strong>Name:</strong> LSUC Finance<br><br>After payment, send your transaction ID and name to the Finance Office.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-circle-exclamation"></i></div>
            <h3>Important Notes</h3>
            <ul style="padding-left:20px;color:var(--gray-600);line-height:1.9">
                <li>Always quote your full name and program</li>
                <li>Keep your payment receipt safe</li>
                <li>Fees are non-refundable once enrollment is complete</li>
                <li>Contact Finance for payment plans</li>
            </ul>
        </div>
    </div>
    <div style="background:var(--gray-100);border-radius:15px;padding:35px;margin-top:40px;text-align:center">
        <h3 style="color:var(--primary-green);margin-bottom:15px"><i class="fa fa-download me-2"></i>Download Fees Structure</h3>
        <p style="color:var(--gray-600);margin-bottom:20px">Download our latest fees structure for detailed tuition and other charges.</p>
        <button onclick="openPDFViewer('{{ asset('docs/fees-structure.pdf') }}','Fees Structure')" class="cta-button"><i class="fa fa-file-pdf me-2"></i>View Fees Structure</button>
    </div>
</div></section>
@endsection
