@extends('layouts.app')
@section('title', 'Student Portal - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>Student Portal</h1>
    <p>Access your academic records, results, and timetables</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; Student Portal
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Portal Access</h2><p>Log in to your portal to manage your academic journey.</p></div>
    <div class="cards-grid">
        <div class="card" style="text-align:center">
            <div class="card-icon" style="margin:0 auto 20px"><i class="fa fa-id-card"></i></div>
            <h3>Student Portal</h3>
            <p>Access your academic records, view results, download transcripts, and track your fee balance.</p>
            <a href="{{ config('site.student_portal') }}" target="_blank" class="cta-button" style="margin-top:20px;display:inline-block"><i class="fa fa-arrow-right-to-bracket me-2"></i>Login to Student Portal</a>
        </div>
        <div class="card" style="text-align:center">
            <div class="card-icon" style="margin:0 auto 20px;background:linear-gradient(135deg,var(--primary-green),var(--dark-green))"><i class="fa fa-chalkboard-user"></i></div>
            <h3>Staff Portal</h3>
            <p>Staff members can access attendance records, mark submissions, and academic management tools.</p>
            <a href="{{ config('site.staff_portal') }}" target="_blank" class="cta-button" style="margin-top:20px;display:inline-block;background:var(--primary-green)"><i class="fa fa-arrow-right-to-bracket me-2"></i>Login to Staff Portal</a>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fa fa-circle-question"></i></div>
            <h3>Portal Help</h3>
            <p>Need help accessing the portal? Download our step-by-step guide or contact the ICT department.</p>
            <button onclick="openPDFViewer('{{ asset('docs/Student-Portal-Access-Guide.pdf') }}','Student Portal Access Guide')" class="cta-button-outline" style="margin-top:15px"><i class="fa fa-file-pdf me-2"></i>Download Access Guide</button>
        </div>
    </div>
</div></section>
@endsection
