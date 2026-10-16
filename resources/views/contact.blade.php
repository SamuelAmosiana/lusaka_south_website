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
    @if(session('success'))
    <div class="alert-success"><i class="fa fa-circle-check me-2"></i>{!! session('success') !!}</div>
    @endif
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
    <div style="max-width:700px;margin:60px auto 0">
        <h2 style="color:var(--primary-green);text-align:center;margin-bottom:30px"><i class="fa fa-envelope me-2"></i>Send Us a Message</h2>
        <div style="background:#fff;padding:40px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.08)">
            <form method="POST" action="{{ route('contact.store') }}">
                @csrf
                @if($errors->any())
                <div class="alert-error"><i class="fa fa-triangle-exclamation me-2"></i>Please fix the errors below.</div>
                @endif
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Your full name">
                        @error('name')<span style="color:#d32f2f;font-size:.85rem">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="your@email.com">
                        @error('email')<span style="color:#d32f2f;font-size:.85rem">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+260-XXX-XXX-XXX">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Select Subject</option>
                            <option value="Admissions Inquiry" {{ old('subject')==='Admissions Inquiry'?'selected':'' }}>Admissions Inquiry</option>
                            <option value="Program Information" {{ old('subject')==='Program Information'?'selected':'' }}>Program Information</option>
                            <option value="Fees & Payment" {{ old('subject')==='Fees & Payment'?'selected':'' }}>Fees &amp; Payment</option>
                            <option value="Student Portal Support" {{ old('subject')==='Student Portal Support'?'selected':'' }}>Student Portal Support</option>
                            <option value="Corporate Training" {{ old('subject')==='Corporate Training'?'selected':'' }}>Corporate Training</option>
                            <option value="General Inquiry" {{ old('subject')==='General Inquiry'?'selected':'' }}>General Inquiry</option>
                        </select>
                        @error('subject')<span style="color:#d32f2f;font-size:.85rem">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="5" required placeholder="How can we help you?">{{ old('message') }}</textarea>
                    @error('message')<span style="color:#d32f2f;font-size:.85rem">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="submit-btn"><i class="fa fa-paper-plane me-2"></i>Send Message</button>
            </form>
        </div>
    </div>
</div></section>
@endsection
