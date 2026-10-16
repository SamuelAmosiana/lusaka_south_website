@extends('layouts.app')
@section('title','Research Repository - '.config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>Research Repository</h1><p>Explore academic research from our faculty and students</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; Research Repository</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Research &amp; Publications</h2><p>Access our growing collection of research publications and student theses.</p></div>
    <div class="cards-grid">
        <div class="card"><div class="card-icon"><i class="fa fa-microscope"></i></div><h3>Student Research</h3><p>Browse dissertations and research projects completed by our graduating students.</p></div>
        <div class="card"><div class="card-icon"><i class="fa fa-flask"></i></div><h3>Faculty Publications</h3><p>Access research papers and publications authored by our academic staff.</p></div>
        <div class="card" style="text-align:center">
            <div class="card-icon" style="margin:0 auto 20px"><i class="fa fa-database"></i></div>
            <h3>Access Repository</h3>
            <p>Our research repository is hosted on DSpace, an open-access platform for academic publications.</p>
            <a href="{{ config('site.research') }}" target="_blank" class="cta-button" style="margin-top:20px;display:inline-block"><i class="fa fa-arrow-right-to-bracket me-2"></i>Open Repository</a>
        </div>
    </div>
</div></section>
@endsection
