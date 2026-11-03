@extends('layouts.app')
@section('title', 'Contact Us - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>Contact Us</h1>
    <p>We're here to help. Reach out to the right department below.</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; Contact
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Get In Touch</h2>
        <p>Our team is ready to assist you with admissions, academics, finance and more.</p>
    </div>
    <div class="cards-grid">
        <div class="card"><div class="card-icon"><i class="fa fa-graduation-cap"></i></div>
            <h3>Admissions Office</h3>
            <p><i class="fa fa-phone me-2" style="color:var(--primary-orange)"></i>{{ config('site.phone') }}</p>
            <p><i class="fa fa-envelope me-2" style="color:var(--primary-orange)"></i>{{ config('site.admissions_email') }}</p>
            <p>For queries about application requirements, deadlines, and enrollment procedures.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-book-open"></i></div>
            <h3>Academic Affairs</h3>
            <p><i class="fa fa-phone me-2" style="color:var(--primary-orange)"></i>{{ config('site.phone') }}</p>
            <p><i class="fa fa-envelope me-2" style="color:var(--primary-orange)"></i>academics@lsc.edu.zm</p>
            <p>For academic timetables, course information, and examination matters.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-building-columns"></i></div>
            <h3>Finance &amp; Accounts</h3>
            <p><i class="fa fa-phone me-2" style="color:var(--primary-orange)"></i>{{ config('site.phone') }}</p>
            <p><i class="fa fa-envelope me-2" style="color:var(--primary-orange)"></i>finance@lsc.edu.zm</p>
            <p>For tuition fees, payment plans, and financial clearance enquiries.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-laptop"></i></div>
            <h3>ICT &amp; Student Portal</h3>
            <p><i class="fa fa-phone me-2" style="color:var(--primary-orange)"></i>{{ config('site.phone2') }}</p>
            <p><i class="fa fa-envelope me-2" style="color:var(--primary-orange)"></i>ict@lsc.edu.zm</p>
            <p>For student portal access, eLearning support, and technical assistance.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-briefcase"></i></div>
            <h3>Corporate Training</h3>
            <p><i class="fa fa-phone me-2" style="color:var(--primary-orange)"></i>{{ config('site.phone') }}</p>
            <p><i class="fa fa-envelope me-2" style="color:var(--primary-orange)"></i>training@lsc.edu.zm</p>
            <p>For corporate training proposals, customized programs, and partnership inquiries.</p>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-location-dot"></i></div>
            <h3>Visit Us</h3>
            <p><strong>{{ config('site.address') }}</strong></p>
            <p>Monday – Friday: 8:00 AM – 5:00 PM<br>Saturday: 9:00 AM – 1:00 PM</p>
        </div>
    </div>

    {{-- Contact Form --}}
    <div id="contact-form" style="max-width:700px;margin:60px auto 0;scroll-margin-top:100px">
        <h2 style="color:var(--primary-green);text-align:center;margin-bottom:30px"><i class="fa fa-envelope me-2"></i>Send Us a Message</h2>
        <div style="background:#fff;padding:40px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.08)">
            @include('partials.contact-form')
        </div>
    </div>
</div></section>
@endsection
