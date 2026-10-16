@extends('layouts.app')
@section('title', 'Partnerships - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>Partnerships &amp; Collaborations</h1>
    <p>Building bridges between academia and industry</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; <a href="{{ route('about') }}">About Us</a> &rsaquo; Partnerships
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Our Partnerships</h2>
        <p>Strategic alliances that enhance our educational offerings and student opportunities.</p>
    </div>
    <div class="cards-grid">
        <div class="card"><div class="card-icon"><i class="fa fa-industry"></i></div><h3>Industry Partners</h3>
            <p>We collaborate with leading companies across Zambia's key industries to ensure our curricula remain relevant and to provide students with practical work experience opportunities.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-globe"></i></div><h3>International Collaborations</h3>
            <p>Our international partnerships expose students to global best practices and open doors to further education and career opportunities beyond Zambia's borders.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-landmark"></i></div><h3>Government &amp; Regulatory Bodies</h3>
            <p>We work closely with government ministries, regulatory bodies, and professional associations to align our programs with national development goals.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-handshake"></i></div><h3>Corporate Training Partners</h3>
            <p>We partner with organizations to deliver customized corporate training programs that build workforce capacity and drive organizational performance.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-flask"></i></div><h3>Research Partners</h3>
            <p>Academic and research collaborations help us contribute to knowledge creation and ensure our faculty remain at the cutting edge of their disciplines.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-money-bill-wave"></i></div><h3>Scholarship Partners</h3>
            <p>Through partnerships with funding organizations, we secure scholarships and bursaries that make quality education accessible to deserving students.</p>
        </div>
    </div>
    <div style="text-align:center;margin-top:50px">
        <a href="{{ route('contact') }}" class="cta-button"><i class="fa fa-envelope me-2"></i>Partner With Us</a>
    </div>
</div></section>
@endsection
