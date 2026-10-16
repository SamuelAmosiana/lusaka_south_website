@extends('layouts.app')
@section('title', 'FAQs - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>Frequently Asked Questions</h1>
    <p>Answers to the most common questions about studying at {{ config('site.short_name') }}</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; FAQs
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>FAQs</h2>
        <p>Can't find what you're looking for? <a href="{{ route('contact') }}" style="color:var(--primary-orange)">Contact us directly</a>.</p>
    </div>
    @forelse($faqs as $category => $items)
    <h3 style="color:var(--primary-green);font-size:1.4rem;margin:40px 0 20px;border-left:4px solid var(--primary-orange);padding-left:15px">
        <i class="fa fa-folder-open me-2"></i>{{ $category }}
    </h3>
    <div class="cards-grid" style="grid-template-columns:repeat(auto-fit,minmax(450px,1fr))">
        @foreach($items as $i => $faq)
        <div class="card">
            <div class="faq-header">
                <div class="faq-number">{{ $i+1 }}</div>
                <span class="faq-category-badge">{{ $faq->category }}</span>
            </div>
            <h3 style="color:var(--gray-800);font-size:1.05rem;margin-bottom:10px">{{ $faq->question }}</h3>
            <div class="faq-answer">{{ $faq->answer }}</div>
        </div>
        @endforeach
    </div>
    @empty
    <p style="text-align:center;color:var(--gray-600);padding:50px">No FAQs available yet. <a href="{{ route('contact') }}">Contact us</a> for assistance.</p>
    @endforelse
    <div style="text-align:center;margin-top:60px;background:var(--gray-100);padding:40px;border-radius:15px">
        <i class="fa fa-circle-question fa-3x" style="color:var(--primary-orange);margin-bottom:20px"></i>
        <h3 style="color:var(--primary-green);margin-bottom:12px">Still have questions?</h3>
        <p style="color:var(--gray-600);margin-bottom:20px">Our admissions team is ready to help you with any questions.</p>
        <a href="{{ route('contact') }}" class="cta-button"><i class="fa fa-envelope me-2"></i>Contact Us</a>
    </div>
</div></section>
@endsection
