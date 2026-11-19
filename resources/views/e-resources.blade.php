@extends('layouts.app')
@section('title', $page->header_title . ' - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container"><h1>{{ $page->header_title }}</h1>@if($page->header_subtitle)<p>{{ $page->header_subtitle }}</p>@endif</div></div>
<div class="breadcrumb"><div class="container"><a href="{{ route('home') }}">Home</a> &rsaquo; E-Resources</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>{{ $page->section_title }}</h2>@if($page->section_subtitle)<p>{{ $page->section_subtitle }}</p>@endif</div>
    <div class="cards-grid">
        @foreach($page->activeItems('cards') as $resource)
        <div class="card"><div class="card-icon"><i class="fa {{ $resource->icon }}"></i></div><h3>{{ $resource->title }}</h3><p>{{ $resource->body }}</p>
            <a href="{{ $resource->url }}" target="_blank" rel="noopener" class="cta-button-outline" style="margin-top:15px;display:inline-block"><i class="fa fa-arrow-up-right-from-square me-1"></i>{{ $resource->subtitle ?: 'Visit Website' }}</a>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:50px">
        <a href="{{ route('services.elibrary') }}" class="cta-button"><i class="fa fa-book me-2"></i>Visit Our Digital Library</a>
    </div>
</div></section>
@endsection
