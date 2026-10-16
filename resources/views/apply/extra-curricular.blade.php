@extends('layouts.app')
@section('title','Extra-Curricular Activities - '.config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>Extra-Curricular Activities</h1><p>Beyond the classroom — enriching student life at LSUC</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; Extra-Curricular</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Student Life</h2><p>We believe education extends beyond the classroom. Participate in clubs, sports, and community initiatives.</p></div>
    <div class="cards-grid">
        <div class="card"><div class="card-icon"><i class="fa fa-futbol"></i></div><h3>Sports &amp; Athletics</h3><p>Join our football, basketball, volleyball, and athletics teams. Compete in inter-college tournaments.</p></div>
        <div class="card"><div class="card-icon"><i class="fa fa-microphone"></i></div><h3>Debate &amp; Public Speaking</h3><p>Sharpen your communication skills with our debate club and public speaking workshops.</p></div>
        <div class="card"><div class="card-icon"><i class="fa fa-music"></i></div><h3>Music &amp; Arts</h3><p>Express your creativity through our choir, drama club, and arts &amp; crafts groups.</p></div>
        <div class="card"><div class="card-icon"><i class="fa fa-laptop-code"></i></div><h3>Tech Club</h3><p>Innovate and build with the LSUC Technology Club — coding challenges, hackathons, and more.</p></div>
        <div class="card"><div class="card-icon"><i class="fa fa-hands-helping"></i></div><h3>Community Service</h3><p>Give back to the community through our outreach and volunteer programs.</p></div>
        <div class="card"><div class="card-icon"><i class="fa fa-users"></i></div><h3>Student Government</h3><p>Represent your fellow students in the Student Representative Council and shape campus life.</p></div>
    </div>
    <div style="text-align:center;margin-top:40px">
        <a href="{{ route('contact') }}" class="cta-button"><i class="fa fa-envelope me-2"></i>Enquire About Activities</a>
    </div>
</div></section>
@endsection
