@extends('layouts.app')
@section('title','E-Resources - '.config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>E-Resources Portal</h1><p>Access digital learning resources and academic databases</p></div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; E-Resources</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Digital Learning Resources</h2><p>Curated online resources to support your academic work.</p></div>
    <div class="cards-grid">
        <div class="card"><div class="card-icon"><i class="fa fa-book"></i></div><h3>OpenStax</h3><p>Free, peer-reviewed textbooks for college and university courses across multiple disciplines.</p>
            <a href="https://openstax.org" target="_blank" class="cta-button-outline" style="margin-top:15px;display:inline-block"><i class="fa fa-arrow-up-right-from-square me-1"></i>Visit OpenStax</a>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-scroll"></i></div><h3>Google Scholar</h3><p>Search scholarly literature across many disciplines and sources: theses, books, abstracts, and articles.</p>
            <a href="https://scholar.google.com" target="_blank" class="cta-button-outline" style="margin-top:15px;display:inline-block"><i class="fa fa-arrow-up-right-from-square me-1"></i>Visit Scholar</a>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-graduation-cap"></i></div><h3>MIT OpenCourseWare</h3><p>Free online course materials from MIT covering nearly all undergraduate and graduate subjects.</p>
            <a href="https://ocw.mit.edu" target="_blank" class="cta-button-outline" style="margin-top:15px;display:inline-block"><i class="fa fa-arrow-up-right-from-square me-1"></i>Visit MIT OCW</a>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-play-circle"></i></div><h3>Khan Academy</h3><p>Free lessons in math, science, computing, humanities, and more, for learners of all levels.</p>
            <a href="https://www.khanacademy.org" target="_blank" class="cta-button-outline" style="margin-top:15px;display:inline-block"><i class="fa fa-arrow-up-right-from-square me-1"></i>Visit Khan Academy</a>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-database"></i></div><h3>DOAJ</h3><p>Directory of Open Access Journals — a comprehensive index of high-quality, open-access peer-reviewed journals.</p>
            <a href="https://doaj.org" target="_blank" class="cta-button-outline" style="margin-top:15px;display:inline-block"><i class="fa fa-arrow-up-right-from-square me-1"></i>Visit DOAJ</a>
        </div>
        <div class="card"><div class="card-icon"><i class="fa fa-globe-africa"></i></div><h3>African Journals Online</h3><p>Access to peer-reviewed, African-published journals across a range of disciplines.</p>
            <a href="https://www.ajol.info" target="_blank" class="cta-button-outline" style="margin-top:15px;display:inline-block"><i class="fa fa-arrow-up-right-from-square me-1"></i>Visit AJOL</a>
        </div>
    </div>
    <div style="text-align:center;margin-top:50px">
        <a href="{{ route('services.elibrary') }}" class="cta-button"><i class="fa fa-book me-2"></i>Visit Our Digital Library</a>
    </div>
</div></section>
@endsection
