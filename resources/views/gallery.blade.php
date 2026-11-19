@extends('layouts.app')
@section('title', 'Gallery - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>Gallery</h1>
    <p>A visual journey through our campus and student life</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; Gallery
</div></div>
<section class="content-section"><div class="container">
    @forelse($sections as $section)
    <div class="section-title" @if(!$loop->first) style="margin-top:70px" @endif><h2>{{ $section->title }}</h2>
        @if($section->subtitle)<p>{{ $section->subtitle }}</p>@endif
    </div>
    <div class="gallery-grid">
        @foreach($section->activePhotos as $photo)
            @continue(!$photo->image_url)
        <div class="gallery-item" onclick="openImagePreview(this)" data-image="{{ $photo->image_url }}">
            <img src="{{ $photo->image_url }}" alt="{{ $photo->caption }}" loading="lazy">
            @if($photo->caption)<div class="gallery-overlay"><h4>{{ $photo->caption }}</h4></div>@endif
        </div>
        @endforeach
    </div>
    @empty
    <p style="text-align:center;color:var(--gray-600);padding:50px">Photos are coming soon.</p>
    @endforelse
</div></section>
@endsection
