@extends('layouts.app')
@section('title','Donate - '.config('site.name'))
@section('styles')
@include('pay._styles')
@endsection
@section('content')
<div class="page-header"><div class="container"><h1>Donate to {{ config('site.short_name') }}</h1><p>Your generosity transforms lives — help us build a brighter future for our students</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; <a href="{{ route('pay.online') }}">Pay Online</a> &rsaquo; Donate</div></div>
<section class="content-section"><div class="container">
    <div class="donate-intro">
        <div class="pay-banner-icon"><i class="fa fa-hand-holding-heart"></i></div>
        <h2>Make a Difference Today</h2>
        <p>Every donation, large or small, goes directly towards supporting vulnerable students, expanding classrooms, purchasing learning materials, and building a stronger institution. {{ config('site.name') }} is grateful for your support.</p>
    </div>

    <div class="purpose-box">
        <h3>Choose a Donation Purpose</h3>
        <div class="purpose-options" role="radiogroup" aria-label="Donation purpose">
            @foreach([
                'Vulnerable Students'  => 'fa-user-graduate',
                'Classroom Expansion'  => 'fa-school',
                'Library & Materials'  => 'fa-book-open',
                'Sports & Activities'  => 'fa-futbol',
                'General Donation'     => 'fa-hand-holding-heart',
            ] as $purpose => $icon)
                <input type="radio" name="purpose" id="purpose-{{ $loop->index }}" value="{{ $purpose }}" @checked($loop->last)>
                <label for="purpose-{{ $loop->index }}"><i class="fa {{ $icon }}"></i>{{ $purpose }}</label>
            @endforeach
        </div>
        <div class="purpose-other">
            <label for="purpose-custom">Or specify:</label>
            <input type="text" id="purpose-custom" maxlength="100" placeholder="e.g. Donating for ICT Lab equipment">
        </div>
        <p class="purpose-ref"><i class="fa fa-circle-info me-1"></i>Quote this reference when you donate: <strong id="purpose-ref">Donation – General Donation</strong></p>
    </div>

    <div class="section-title" style="margin:50px 0 0"><h2>Choose How to Donate</h2></div>
    @include('pay._methods', ['verb' => 'Donate', 'copy' => [
        'card'   => 'Donate securely using your Visa, Mastercard or bank debit/credit card via CyberSource checkout.',
        'mobile' => 'Donate via MTN Mobile Money, Airtel Money or Zamtel eMoney. Fast, easy, and available 24/7.',
        'bank'   => 'Transfer directly to our ZANACO account from your internet banking or by visiting any branch.',
    ]])

    <div class="pay-banner">
        <div class="pay-banner-icon" style="background:var(--primary-orange)"><i class="fa fa-star"></i></div>
        <div>
            <h3>Thank You for Your Generosity</h3>
            <p>All donations are used transparently and directly benefit {{ config('site.short_name') }} students. For donation receipts or enquiries please contact us on
                <a href="tel:{{ preg_replace('/\s+/', '', config('site.payments.finance_phone')) }}">{{ config('site.payments.finance_phone') }}</a>
                or email <a href="mailto:{{ config('site.payments.donations_email') }}">{{ config('site.payments.donations_email') }}</a>.</p>
        </div>
    </div>
</div></section>
@endsection
@section('scripts')
@stack('pay-scripts')
<script>
(function () {
    const custom = document.getElementById('purpose-custom');
    const ref = document.getElementById('purpose-ref');
    const radios = document.querySelectorAll('input[name="purpose"]');
    function update() {
        const typed = custom.value.trim();
        const picked = document.querySelector('input[name="purpose"]:checked');
        ref.textContent = 'Donation – ' + (typed || (picked ? picked.value : 'General Donation'));
    }
    radios.forEach(r => r.addEventListener('change', () => { custom.value = ''; update(); }));
    custom.addEventListener('input', update);
})();
</script>
@endsection
