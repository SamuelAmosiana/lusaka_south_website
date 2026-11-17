@extends('layouts.app')
@section('title', $page->header_title . ' - ' . config('site.name'))
@section('content')
@include('about.partials.header')
<section class="content-section">
    <div class="container">
        <div class="section-title"><h2>{{ $page->section_title }}</h2>
            @if($page->section_subtitle)<p>{{ $page->section_subtitle }}</p>@endif
        </div>
        @include('about.partials.people', ['group' => 'leadership'])

        @if($page->activeItems('hods')->isNotEmpty())
        <div class="section-title" style="margin-top:80px"><h2>{{ $page->extra_title ?: 'Heads of Departments' }}</h2>
            @if($page->extra_text)<p>{{ $page->extra_text }}</p>@endif
        </div>
        @include('about.partials.people', ['group' => 'hods'])
        @endif
    </div>
</section>
@endsection
