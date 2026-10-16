@extends('layouts.app')
@section('title','E-Library - '.config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>Digital Library</h1><p>Access thousands of e-books, journals, and academic resources</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; E-Library</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Digital Library Resources</h2><p>Our e-library gives students access to a wide range of academic resources.</p></div>
    <div class="cards-grid">
        <div class="card"><div class="card-icon"><i class="fa fa-book"></i></div><h3>E-Books</h3><p>Access thousands of digital textbooks and reference materials across all disciplines.</p></div>
        <div class="card"><div class="card-icon"><i class="fa fa-scroll"></i></div><h3>Academic Journals</h3><p>Browse peer-reviewed journals and research publications from reputable publishers.</p></div>
        <div class="card"><div class="card-icon"><i class="fa fa-database"></i></div><h3>Research Databases</h3><p>Access curated research databases relevant to your field of study.</p></div>
        <div class="card" style="text-align:center">
            <div class="card-icon" style="margin:0 auto 20px"><i class="fa fa-globe"></i></div>
            <h3>E-Resources Portal</h3>
            <p>Access the full digital library and external academic databases.</p>
            <a href="{{ route('e-resources') }}" class="cta-button" style="margin-top:20px;display:inline-block"><i class="fa fa-arrow-right me-2"></i>Open E-Resources</a>
        </div>
    </div>
</div></section>
@endsection
