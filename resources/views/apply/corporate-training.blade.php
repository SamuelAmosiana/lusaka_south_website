@extends('layouts.app')
@section('title','Corporate Training - '.config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>Corporate Training Application</h1><p>Professional training solutions for your organization</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; Apply &rsaquo; Corporate Training</div></div>
<section class="content-section"><div class="container">
    @if(session('success'))<div class="alert-success"><i class="fa fa-circle-check me-2"></i>{!! session('success') !!}</div>@endif
    <form class="application-form" method="POST" action="{{ route('apply.corporate-training.submit') }}">
        @csrf
        @if($errors->any())<div class="alert-error">Please fix the errors below.</div>@endif
        <div class="form-info">
            <h4><i class="fa fa-building me-2"></i>Corporate Training Services Available 2026</h4>
            <ul>
                <li>Leadership Development Programs</li><li>Team Building &amp; Communication</li>
                <li>Technical Skills Training</li><li>Customer Service Excellence</li>
                <li>Health &amp; Safety Training</li><li>Customized Training Solutions</li>
            </ul>
        </div>
        <div class="form-section"><h3>Organization Information</h3>
            <div class="form-group"><label for="company">Company/Organization Name *</label><input type="text" id="company" name="company" required value="{{ old('company') }}"></div>
            <div class="form-row">
                <div class="form-group"><label for="industry">Industry *</label>
                    <select id="industry" name="industry" required>
                        <option value="">Select Industry</option>
                        <option value="manufacturing">Manufacturing</option><option value="healthcare">Healthcare</option>
                        <option value="education">Education</option><option value="finance">Finance &amp; Banking</option>
                        <option value="retail">Retail</option><option value="technology">Technology</option>
                        <option value="government">Government</option><option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group"><label for="companysize">Company Size *</label>
                    <select id="companysize" name="companysize" required>
                        <option value="">Select Size</option>
                        <option value="1-10">1-10 employees</option><option value="11-50">11-50 employees</option>
                        <option value="51-200">51-200 employees</option><option value="201-500">201-500 employees</option>
                        <option value="500+">500+ employees</option>
                    </select>
                </div>
            </div>
            <div class="form-group"><label for="address">Company Address *</label><textarea id="address" name="address" rows="3" required>{{ old('address') }}</textarea></div>
        </div>
        <div class="form-section"><h3>Contact Person Details</h3>
            <div class="form-row">
                <div class="form-group"><label for="contactname">Contact Person Name *</label><input type="text" id="contactname" name="contactname" required value="{{ old('contactname') }}"></div>
                <div class="form-group"><label for="position">Position/Title *</label><input type="text" id="position" name="position" required value="{{ old('position') }}"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label for="email">Email Address *</label><input type="email" id="email" name="email" required value="{{ old('email') }}"></div>
                <div class="form-group"><label for="phone">Phone Number *</label><input type="tel" id="phone" name="phone" required value="{{ old('phone') }}"></div>
            </div>
        </div>
        <div class="form-section"><h3>Training Requirements</h3>
            <div class="form-group"><label for="trainingtype">Training Type *</label>
                <select id="trainingtype" name="trainingtype" required>
                    <option value="">Select Training Type</option>
                    <option value="leadership">Leadership Development Programs</option>
                    <option value="team-building">Team Building &amp; Communication</option>
                    <option value="technical">Technical Skills Training</option>
                    <option value="customer-service">Customer Service Excellence</option>
                    <option value="health-safety">Health &amp; Safety Training</option>
                    <option value="custom">Customized Training Solutions</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label for="participants">Number of Participants *</label><input type="number" id="participants" name="participants" min="1" required value="{{ old('participants') }}"></div>
                <div class="form-group"><label for="duration">Preferred Duration *</label>
                    <select id="duration" name="duration" required>
                        <option value="">Select Duration</option>
                        <option value="half-day">Half Day (4 hours)</option><option value="full-day">Full Day (8 hours)</option>
                        <option value="2-days">2 Days</option><option value="1-week">1 Week</option><option value="custom">Custom Duration</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label for="location">Training Location *</label>
                    <select id="location" name="location" required>
                        <option value="">Select Location</option>
                        <option value="on-site">On-site (Your premises)</option><option value="lsuc-campus">LSUC Campus</option>
                        <option value="online">Online Training</option><option value="hybrid">Hybrid (Online + On-site)</option>
                    </select>
                </div>
                <div class="form-group"><label for="budget">Budget Range</label>
                    <select id="budget" name="budget">
                        <option value="">Select Budget Range</option>
                        <option value="under-5000">Under K5,000</option><option value="5000-15000">K5,000 – K15,000</option>
                        <option value="15000-30000">K15,000 – K30,000</option><option value="over-30000">Over K30,000</option>
                        <option value="discuss">To be discussed</option>
                    </select>
                </div>
            </div>
            <div class="form-group"><label for="specificneeds">Specific Training Needs/Objectives *</label>
                <textarea id="specificneeds" name="specificneeds" rows="4" required placeholder="Describe your training objectives, challenges, and desired outcomes">{{ old('specificneeds') }}</textarea>
            </div>
            <div class="form-group"><label for="timeline">Preferred Timeline</label>
                <textarea id="timeline" name="timeline" rows="2" placeholder="When would you like to conduct this training?">{{ old('timeline') }}</textarea>
            </div>
        </div>
        <button type="submit" class="submit-btn"><i class="fa fa-paper-plane me-2"></i>Request Training Proposal</button>
    </form>
</div></section>
@endsection
