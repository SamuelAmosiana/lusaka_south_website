@extends('layouts.app')
@section('title', $page->header_title . ' - ' . config('site.name'))
@section('content')
@include('about.partials.header')
<section class="content-section"><div class="container">
    <div class="section-title"><h2>{{ $page->section_title }}</h2>
        @if($page->section_subtitle)<p>{{ $page->section_subtitle }}</p>@endif
    </div>
    @include('about.partials.cards')
    <div style="text-align:center;margin-top:50px">
        <a href="{{ route('contact') }}" class="cta-button"><i class="fa fa-envelope me-2"></i>Partner With Us</a>
    </div>
</div></section>
@endsection
