@extends('layouts.app')
@section('title', 'Our Team - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>Our Team</h1>
    <p>Meet the dedicated professionals leading {{ config('site.short_name') }}</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; <a href="{{ route('about') }}">About Us</a> &rsaquo; Our Team
</div></div>
<section class="content-section">
    <div class="container">
        <div class="section-title"><h2>Leadership Team</h2>
            <p>Experienced professionals guiding our institution to excellence.</p>
        </div>
        <div class="cards-grid" style="grid-template-columns:repeat(auto-fit,minmax(250px,1fr));text-align:center">
            @php
            $leadership = [
                ['name'=>'Vice Principal','title'=>'Vice Principal','img'=>'Vice-Principal.jpg','desc'=>'Providing strategic oversight and academic direction for the institution.'],
                ['name'=>'Registrar','title'=>'Registrar','img'=>'Registrar.jpg','desc'=>'Managing academic records, admissions, and student administration.'],
                ['name'=>'Academic Dean','title'=>'Academic Dean','img'=>'Academic-Dean.jpg','desc'=>'Overseeing academic programs and ensuring quality education standards.'],
                ['name'=>'Enrollments Officer','title'=>'Enrollments Officer','img'=>'Enrollments-Officer.jpg','desc'=>'Facilitating student enrollment and admissions processes.'],
                ['name'=>'Systems Administrator','title'=>'Systems Administrator','img'=>'Systems-Administrator.jpg','desc'=>'Managing ICT infrastructure and digital learning systems.'],
                ['name'=>'Front Desk Officer','title'=>'Front Desk Officer','img'=>'front-desk-officer.jpg','desc'=>'The first point of contact for students, staff, and visitors.'],
            ];
            @endphp
            @foreach($leadership as $member)
            <div class="card" style="text-align:center">
                <div class="staff-image-container">
                    <img src="{{ asset('images/staff/'.$member['img']) }}" alt="{{ $member['name'] }}" class="staff-image">
                </div>
                <h3>{{ $member['name'] }}</h3>
                <p style="color:var(--primary-orange);font-weight:600;margin-bottom:10px">{{ $member['title'] }}</p>
                <p>{{ $member['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="section-title" style="margin-top:80px"><h2>Heads of Departments</h2>
            <p>Academic leaders driving excellence in their respective schools.</p>
        </div>
        <div class="cards-grid" style="grid-template-columns:repeat(auto-fit,minmax(250px,1fr));text-align:center">
            @php
            $hods = [
                ['name'=>'Mr. Bwalya','title'=>'Head – School of Engineering & Technology','img'=>'hod/mr_bwalya_engineering.jpg'],
                ['name'=>'Mr. Machila','title'=>'Head – School of Business & Management','img'=>'hod/mr_machila_business.jpg'],
                ['name'=>'Mr. Sianamate','title'=>'Head – School of Information Technology','img'=>'hod/mr_sianamate_it.jpg'],
                ['name'=>'Mr. Chido','title'=>'Head – School of Health Sciences','img'=>'hod/mr_chido_health_sciences.jpg'],
            ];
            @endphp
            @foreach($hods as $hod)
            <div class="card" style="text-align:center">
                <div class="staff-image-container">
                    <img src="{{ asset('images/'.$hod['img']) }}" alt="{{ $hod['name'] }}" class="staff-image">
                </div>
                <h3>{{ $hod['name'] }}</h3>
                <p style="color:var(--primary-orange);font-weight:600">{{ $hod['title'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
