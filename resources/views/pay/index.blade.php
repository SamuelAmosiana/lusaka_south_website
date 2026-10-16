@extends('layouts.app')
@section('title','Online Payments - '.config('site.name'))
@section('styles')
@include('pay._styles')
@endsection
@section('content')
<div class="page-header"><div class="container"><h1>Online Payments</h1><p>Select your preferred payment method — fast, secure and convenient</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; Online Payments</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Make a Payment</h2><p>Choose how you would like to pay. Click on any option below to get started.</p></div>

    @include('pay._methods', ['verb' => 'Pay', 'copy' => [
        'card'   => 'Pay securely using your Visa, Mastercard, or any bank debit/credit card. Fast and hassle-free.',
        'mobile' => 'Pay using MTN Mobile Money, Airtel Money, or Zamtel eMoney. Quick, easy and available 24/7.',
        'bank'   => 'Pay directly from your bank account via internet banking or visit any ZANACO branch to make a deposit.',
    ]])

    <div class="pay-banner">
        <div class="pay-banner-icon"><i class="fa fa-shield-halved"></i></div>
        <div>
            <h3>Secure &amp; Trusted Payments</h3>
            <p>All transactions are handled securely. For payment queries or confirmation, contact the Finance Office on
                <a href="tel:{{ preg_replace('/\s+/', '', config('site.payments.finance_phone')) }}">{{ config('site.payments.finance_phone') }}</a>.</p>
        </div>
    </div>
</div></section>
@endsection
@section('scripts')
@stack('pay-scripts')
@endsection
