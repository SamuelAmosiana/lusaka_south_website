@extends('layouts.app')
@section('title','eLearning - '.config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>eLearning Platform</h1><p>Access your online courses and learning materials</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; eLearning</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Online Learning</h2><p>Study flexibly with our Moodle-based eLearning platform.</p></div>
    <div class="cards-grid">
        <div class="card" style="text-align:center">
            <div class="card-icon" style="margin:0 auto 20px"><i class="fa fa-book-open"></i></div>
            <h3>Access eLearning</h3>
            <p>Log in to Moodle to access your course materials, assignments, quizzes, and connect with instructors.</p>
            <a href="{{ config('site.elearning') }}" target="_blank" class="cta-button" style="margin-top:20px;display:inline-block"><i class="fa fa-arrow-right-to-bracket me-2"></i>Go to eLearning</a>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-wifi"></i></div><h3>Online Study Mode</h3><p>Our online programs allow you to study from anywhere in Zambia or abroad while maintaining full access to academic support.</p></div>
        <div class="card"><div class="card-icon"><i class="fa fa-headset"></i></div><h3>eLearning Support</h3><p>Having trouble logging in? Contact our ICT team at ict@lsc.edu.zm or call {{ config('site.phone2') }}.</p></div>
    </div>
</div></section>
@endsection
