@extends('layouts.app')
@section('title', 'About LSUC - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>About LSUC</h1>
    <p>Our story, mission, and vision for higher education in Zambia</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; About Us &rsaquo; LSUC Overview
</div></div>
<section class="content-section">
    <div class="container">
        <div class="section-title"><h2>LSUC Overview</h2>
            <p>Transforming lives through quality education and skills development.</p>
        </div>
        <div class="cards-grid">
            <div class="card">
                <div class="card-icon"><i class="fa fa-book-open"></i></div>
                <h3>Our History</h3>
                <p>Lusaka South University College (LSUC) was established to address the growing need for quality, industry-aligned higher education in Zambia. Built on a foundation of academic excellence, we have grown to become a premier institution for skills development and professional training.</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fa fa-bullseye"></i></div>
                <h3>Our Mission</h3>
                <p>To provide accessible, affordable, and high-quality education and training that empowers individuals with the skills and knowledge needed to excel in their careers and contribute meaningfully to the development of Zambia.</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fa fa-eye"></i></div>
                <h3>Our Vision</h3>
                <p>To be the leading higher education institution in Zambia, recognized for academic excellence, innovation, and producing graduates who are ready for the challenges of the modern workforce.</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fa fa-building"></i></div>
                <h3>Our Campus</h3>
                <p>Located in the serene Foxdale area of Lusaka, our campus offers modern facilities including well-equipped classrooms, computer labs, a science laboratory, a library, sports facilities, and a cafeteria — all designed to support a holistic learning experience.</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fa fa-certificate"></i></div>
                <h3>Accreditation</h3>
                <p>LSUC is registered and accredited by the Higher Education Authority (HEA) of Zambia and the Technical Education, Vocational and Entrepreneurship Training Authority (TEVETA), ensuring that our qualifications are recognized nationally and internationally.</p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fa fa-industry"></i></div>
                <h3>Industry Partnerships</h3>
                <p>We maintain strong partnerships with leading organizations across various sectors, providing our students with internship opportunities, industry exposure, and employment pathways upon graduation.</p>
            </div>
        </div>
        <div style="text-align:center;margin-top:50px;display:flex;gap:15px;justify-content:center;flex-wrap:wrap">
            <a href="{{ config('site.apply.undergraduate') }}" class="cta-button"><i class="fa fa-graduation-cap me-2"></i>Apply Now</a>
            <a href="{{ route('contact') }}" class="cta-button-outline"><i class="fa fa-envelope me-2"></i>Contact Us</a>
        </div>
    </div>
</section>
@endsection
