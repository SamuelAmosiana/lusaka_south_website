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
    <div class="section-title"><h2>Campus Facilities</h2>
        <p>Modern facilities built to support world-class learning.</p>
    </div>
    <div class="gallery-grid">
        @php
        $campus = [
            ['img'=>'computer_lab.jpg','label'=>'Computer Laboratory'],
            ['img'=>'scienc_lab.jpg','label'=>'Science Laboratory'],
            ['img'=>'library.jpg','label'=>'Library'],
            ['img'=>'auditorium.jpg','label'=>'Auditorium'],
            ['img'=>'sports_complex.jpg','label'=>'Sports Complex'],
            ['img'=>'cafteria.jpg','label'=>'Cafeteria'],
            ['img'=>'campus_grounds.jpg','label'=>'Campus Grounds'],
            ['img'=>'hea.jpeg','label'=>'Main Campus'],
        ];
        @endphp
        @foreach($campus as $item)
        <div class="gallery-item" onclick="openImagePreview(this)" data-image="{{ asset('images/'.$item['img']) }}">
            <img src="{{ asset('images/'.$item['img']) }}" alt="{{ $item['label'] }}" loading="lazy">
            <div class="gallery-overlay"><h4>{{ $item['label'] }}</h4></div>
        </div>
        @endforeach
    </div>

    <div class="section-title" style="margin-top:70px"><h2>Student Life</h2>
        <p>Capturing the vibrant life of our students on campus.</p>
    </div>
    <div class="gallery-grid">
        @php
        $studentLife = [
            ['img'=>'culture.jpg','label'=>'Cultural Events'],
            ['img'=>'enrollment.jpeg','label'=>'Enrollment Day'],
            ['img'=>'students_solar tech.jpeg','label'=>'Solar Technology Class'],
            ['img'=>'Health-Sciences.webp','label'=>'Health Sciences'],
        ];
        @endphp
        @foreach($studentLife as $item)
        <div class="gallery-item" onclick="openImagePreview(this)" data-image="{{ asset('images/'.$item['img']) }}">
            <img src="{{ asset('images/'.$item['img']) }}" alt="{{ $item['label'] }}" loading="lazy">
            <div class="gallery-overlay"><h4>{{ $item['label'] }}</h4></div>
        </div>
        @endforeach
    </div>

    <div class="section-title" style="margin-top:70px"><h2>Academic Environment</h2>
        <p>Our schools and learning environments at a glance.</p>
    </div>
    <div class="gallery-grid">
        @php
        $academic = [
            ['img'=>'school1.jpeg','label'=>'School Building'],
            ['img'=>'school2.jpeg','label'=>'Academic Block'],
            ['img'=>'school3.jpeg','label'=>'Campus View'],
            ['img'=>'school4.jpeg','label'=>'Campus Entrance'],
        ];
        @endphp
        @foreach($academic as $item)
        <div class="gallery-item" onclick="openImagePreview(this)" data-image="{{ asset('images/'.$item['img']) }}">
            <img src="{{ asset('images/'.$item['img']) }}" alt="{{ $item['label'] }}" loading="lazy">
            <div class="gallery-overlay"><h4>{{ $item['label'] }}</h4></div>
        </div>
        @endforeach
    </div>
</div></section>
@endsection
