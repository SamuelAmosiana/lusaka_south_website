@extends('layouts.app')
@section('title','Short Course Enrollment - '.config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>Short Course Enrollment</h1><p>Enroll in our professional development short courses</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; Apply &rsaquo; Short Courses</div></div>
<section class="content-section"><div class="container">
    @if(session('success'))<div class="alert-success"><i class="fa fa-circle-check me-2"></i>{!! session('success') !!}</div>@endif
    <form class="application-form" method="POST" action="{{ route('apply.short-courses.submit') }}">
        @csrf
        @if($errors->any())<div class="alert-error"><i class="fa fa-triangle-exclamation me-2"></i>Please fix the highlighted errors.</div>@endif
        <div class="form-info">
            <h4><i class="fa fa-certificate me-2"></i>Short Courses Available 2026</h4>
            <ul>
                <li>Digital Marketing &amp; Social Media</li>
                <li>Project Management Certification</li>
                <li>Computer Applications (Microsoft Office)</li>
                <li>Financial Management &amp; Accounting</li>
                <li>Leadership &amp; Management Skills</li>
                <li>Web Development &amp; Design</li>
            </ul>
        </div>
        <div class="form-section"><h3>Personal Details</h3>
            <div class="form-row">
                <div class="form-group"><label for="firstname">First Name *</label><input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required></div>
                <div class="form-group"><label for="lastname">Last Name *</label><input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label for="email">Email Address *</label><input type="email" id="email" name="email" value="{{ old('email') }}" required></div>
                <div class="form-group"><label for="phone">Phone Number *</label><input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required></div>
            </div>
        </div>
        <div class="form-section"><h3>Course Selection</h3>
            <div class="form-group"><label for="course">Select Course *</label>
                <select id="course" name="course" required>
                    <option value="">Choose Course</option>
                    <option value="digital-marketing">Digital Marketing &amp; Social Media</option>
                    <option value="project-management">Project Management Certification</option>
                    <option value="computer-applications">Computer Applications (Microsoft Office)</option>
                    <option value="financial-management">Financial Management &amp; Accounting</option>
                    <option value="leadership">Leadership &amp; Management Skills</option>
                    <option value="web-development">Web Development &amp; Design</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label for="startdate">Intake *</label>
                    <select id="startdate" name="startdate" required>
                        <option value="">Select Intake</option>
                        <option value="january">January</option>
                        <option value="april">April</option>
                        <option value="july">July</option>
                        <option value="october">October</option>
                    </select>
                </div>
                <div class="form-group"><label for="schedule">Mode of Study *</label>
                    <select id="schedule" name="schedule" required>
                        <option value="">Select Mode</option>
                        <option value="weekdays">Physical (Full Time)</option>
                        <option value="online">Online (Distance)</option>
                    </select>
                </div>
            </div>
            <div class="form-group"><label for="experience">Relevant Experience / Background</label>
                <textarea id="experience" name="experience" rows="3" placeholder="Describe any relevant experience or background"></textarea>
            </div>
            <div class="form-group"><label for="goals">Learning Goals</label>
                <textarea id="goals" name="goals" rows="3" placeholder="What do you hope to achieve from this course?"></textarea>
            </div>
        </div>
        <button type="submit" class="submit-btn"><i class="fa fa-graduation-cap me-2"></i>Enroll Now</button>
    </form>
</div></section>
@endsection
