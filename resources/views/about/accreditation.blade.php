@extends('layouts.app')
@section('title', 'Accreditation - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>Accreditation &amp; Recognition</h1>
    <p>Nationally recognized qualifications you can trust</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; <a href="{{ route('about') }}">About Us</a> &rsaquo; Accreditation
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Our Accreditation</h2>
        <p>{{ config('site.short_name') }} is fully registered and accredited by the relevant regulatory authorities in Zambia.</p>
    </div>
    <div class="cards-grid">
        <div class="card"><div class="card-icon"><i class="fa fa-medal"></i></div><h3>Higher Education Authority (HEA)</h3>
            <p>LSUC is registered and accredited by the Higher Education Authority of Zambia, ensuring our degree and diploma programs meet national academic standards.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-certificate"></i></div><h3>TEVETA Accreditation</h3>
            <p>Our technical and vocational programs are accredited by the Technical Education, Vocational and Entrepreneurship Training Authority (TEVETA), Zambia's key body for TEVET.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-star"></i></div><h3>Professional Associations</h3>
            <p>Several of our programs are recognized by professional associations, allowing graduates to pursue professional membership and certification in their fields.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-globe"></i></div><h3>International Recognition</h3>
            <p>Our qualifications are structured to align with international standards, facilitating recognition of our graduates' credentials in the global job market.</p>
        </div>
    </div>
    <div style="background:var(--gray-100);border-radius:15px;padding:40px;margin-top:50px;text-align:center">
        <h3 style="color:var(--primary-green);margin-bottom:20px"><i class="fa fa-shield-halved me-2"></i>Quality Assurance</h3>
        <p style="max-width:700px;margin:0 auto;color:var(--gray-600);line-height:1.8">We are committed to continuous quality improvement in all our programs and services. Our internal quality assurance processes ensure that we consistently meet and exceed the standards set by our accrediting bodies.</p>
        <a href="{{ route('downloads') }}" class="cta-button" style="margin-top:25px;display:inline-block"><i class="fa fa-download me-2"></i>Download Accreditation Documents</a>
    </div>
</div></section>
@endsection
