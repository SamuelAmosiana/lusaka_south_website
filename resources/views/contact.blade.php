@extends('layouts.app')
@section('title', $page->header_title . ' - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>{{ $page->header_title }}</h1>
    @if($page->header_subtitle)<p>{{ $page->header_subtitle }}</p>@endif
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; Contact
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>{{ $page->section_title }}</h2>
        @if($page->section_subtitle)<p>{{ $page->section_subtitle }}</p>@endif
    </div>
    <div class="cards-grid">
        @foreach($page->activeItems('cards') as $office)
        <div class="card"><div class="card-icon"><i class="fa {{ $office->icon }}"></i></div>
            <h3>{{ $office->title }}</h3>
            @if($office->phone)<p><i class="fa fa-phone me-2" style="color:var(--primary-orange)"></i><a href="tel:{{ preg_replace('/[^+\d]/', '', $office->phone) }}" style="color:inherit;text-decoration:none">{{ $office->phone }}</a></p>@endif
            @if($office->email)<p><i class="fa fa-envelope me-2" style="color:var(--primary-orange)"></i><a href="mailto:{{ $office->email }}" style="color:inherit;text-decoration:none;overflow-wrap:anywhere">{{ $office->email }}</a></p>@endif
            @if($office->body)<p style="white-space:pre-line">{{ $office->body }}</p>@endif
        </div>
        @endforeach
    </div>

    {{-- Contact Form --}}
    <div id="contact-form" style="max-width:700px;margin:60px auto 0;scroll-margin-top:100px">
        <h2 style="color:var(--primary-green);text-align:center;margin-bottom:30px"><i class="fa fa-envelope me-2"></i>{{ $page->extra_title ?: 'Send Us a Message' }}</h2>
        <div style="background:#fff;padding:40px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.08)">
            @include('partials.contact-form')
        </div>
    </div>
</div></section>
@endsection
