@extends('layouts.app')
@section('title','Undergraduate Application - '.config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>Undergraduate Application</h1><p>Begin your academic journey at LSUC</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; Apply &rsaquo; Undergraduate</div></div>
<section class="content-section"><div class="container">
    @if(session('success'))
    <div class="alert-success"><i class="fa fa-circle-check me-2"></i>{!! session('success') !!}</div>
    @endif
    <form class="application-form" method="POST" action="{{ route('apply.undergraduate.submit') }}">
        @csrf
        @if($errors->any())
        <div class="alert-error"><i class="fa fa-triangle-exclamation me-2"></i>Please fix the highlighted errors.</div>
        @endif
        <div class="form-info">
            <h4><i class="fa fa-circle-info me-2"></i>Before You Apply</h4>
            <ul>
                <li>Ensure you meet the minimum entry requirements for your chosen program</li>
                <li>Have your Grade 12 Certificate and NRC ready</li>
                <li>Check payment instructions for the application fee</li>
            </ul>
        </div>
        <div class="form-section"><h3>Personal Information</h3>
            <div class="form-row">
                <div class="form-group"><label for="firstname">First Name *</label><input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required></div>
                <div class="form-group"><label for="lastname">Last Name *</label><input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label for="email">Email Address *</label><input type="email" id="email" name="email" value="{{ old('email') }}" required></div>
                <div class="form-group"><label for="phone">Phone Number *</label><input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label for="dob">Date of Birth *</label><input type="date" id="dob" name="dob" value="{{ old('dob') }}" required></div>
                <div class="form-group"><label for="gender">Gender *</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender')==='male'?'selected':'' }}>Male</option>
                        <option value="female" {{ old('gender')==='female'?'selected':'' }}>Female</option>
                        <option value="other" {{ old('gender')==='other'?'selected':'' }}>Other</option>
                    </select>
                </div>
            </div>
            <div class="form-group"><label for="address">Home Address *</label><textarea id="address" name="address" rows="3" required>{{ old('address') }}</textarea></div>
        </div>
        <div class="form-section"><h3>Program Selection</h3>
            <div class="form-group"><label for="program">Preferred Program *</label>
                <select id="program" name="program" required>
                    <option value="">Choose Program</option>
                    <optgroup label="School of Business &amp; Management">
                        <option value="diploma-business-admin">Diploma in Business Administration</option>
                        <option value="diploma-accounting">Diploma in Accounting &amp; Finance</option>
                        <option value="diploma-hrm">Diploma in Human Resource Management</option>
                        <option value="diploma-marketing">Diploma in Marketing &amp; Sales</option>
                    </optgroup>
                    <optgroup label="School of Information Technology">
                        <option value="diploma-it">Diploma in Information Technology</option>
                        <option value="diploma-software">Diploma in Software Development</option>
                        <option value="diploma-networking">Diploma in Networking &amp; Cybersecurity</option>
                    </optgroup>
                    <optgroup label="School of Engineering &amp; Technology">
                        <option value="diploma-electrical">Diploma in Electrical Engineering</option>
                        <option value="diploma-civil">Diploma in Civil Engineering</option>
                        <option value="diploma-mechanical">Diploma in Mechanical Engineering</option>
                        <option value="diploma-solar">Diploma in Solar &amp; Renewable Energy</option>
                    </optgroup>
                    <optgroup label="School of Health Sciences">
                        <option value="diploma-env-health">Diploma in Environmental Health</option>
                        <option value="diploma-community-health">Diploma in Community Health</option>
                    </optgroup>
                    <optgroup label="School of Education">
                        <option value="diploma-ece">Diploma in Early Childhood Education</option>
                        <option value="diploma-primary">Diploma in Primary Education</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label for="intake">Intake *</label>
                    <select id="intake" name="intake" required>
                        <option value="">Select Intake</option>
                        <option value="january-2026">January 2026</option>
                        <option value="april-2026">April 2026</option>
                        <option value="july-2026">July 2026</option>
                        <option value="october-2026">October 2026</option>
                    </select>
                </div>
                <div class="form-group"><label for="mode_of_study">Mode of Study *</label>
                    <select id="mode_of_study" name="mode_of_study" required>
                        <option value="">Select Mode</option>
                        <option value="full-time">Full Time (Physical)</option>
                        <option value="part-time">Part Time (Evening)</option>
                        <option value="online">Online (Distance)</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-section"><h3>Academic Qualifications</h3>
            <div class="form-group"><label for="qualification">Highest Qualification *</label>
                <select id="qualification" name="qualification" required>
                    <option value="">Select Qualification</option>
                    <option value="grade-12">Grade 12 Certificate</option>
                    <option value="grade-11">Grade 11 Certificate</option>
                    <option value="diploma">Diploma</option>
                    <option value="degree">Degree</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>
        <button type="submit" class="submit-btn"><i class="fa fa-paper-plane me-2"></i>Submit Application</button>
    </form>
</div></section>
@endsection
