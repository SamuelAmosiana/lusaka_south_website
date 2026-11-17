@extends('layouts.app')
@section('title', $page->header_title . ' - ' . config('site.name'))
@section('content')
@include('about.partials.header')
<section class="content-section"><div class="container">
    <div class="section-title"><h2>{{ $page->section_title }}</h2>
        @if($page->section_subtitle)<p>{{ $page->section_subtitle }}</p>@endif
    </div>
    @include('about.partials.cards')
    <div style="background:var(--gray-100);border-radius:15px;padding:40px;margin-top:50px;text-align:center">
        @if($page->extra_title)<h3 style="color:var(--primary-green);margin-bottom:20px"><i class="fa fa-shield-halved me-2"></i>{{ $page->extra_title }}</h3>@endif
        @if($page->extra_text)<p style="max-width:700px;margin:0 auto;color:var(--gray-600);line-height:1.8">{{ $page->extra_text }}</p>@endif
        <a href="{{ route('downloads') }}" class="cta-button" style="margin-top:25px;display:inline-block"><i class="fa fa-download me-2"></i>Download Accreditation Documents</a>
    </div>
</div></section>
@endsection
