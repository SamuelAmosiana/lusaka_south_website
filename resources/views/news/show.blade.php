@extends('layouts.app')
@section('title', $event->title . ' - ' . config('site.name'))
@section('styles')
<style>
.event-layout{display:grid;grid-template-columns:2fr 1fr;gap:40px;margin-top:30px}
.detail-meta{background:var(--gray-100);padding:20px;border-radius:10px;margin-bottom:25px;border-left:4px solid var(--primary-orange)}
.detail-meta p{margin-bottom:8px;color:var(--gray-600)}
.detail-full{font-size:1.05rem;line-height:1.85;color:var(--gray-800)}
.detail-full h3{color:var(--primary-green);margin:25px 0 15px}
.detail-full ul,.detail-full ol{margin:15px 0;padding-left:30px}
.detail-full li{margin-bottom:8px}
.sidebar-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 5px 20px rgba(0,0,0,.08);margin-bottom:20px}
.sidebar-card h4{color:var(--primary-green);margin-bottom:15px;padding-bottom:10px;border-bottom:2px solid var(--primary-orange)}
.related-item{padding:10px 0;border-bottom:1px solid var(--gray-200);display:flex;gap:10px;align-items:flex-start}
.related-item:last-child{border-bottom:none}
.related-item a{color:var(--gray-800);text-decoration:none;font-size:.9rem;line-height:1.4}
.related-item a:hover{color:var(--primary-orange)}
.related-date{font-size:.8rem;color:var(--gray-600)}
@media(max-width:768px){.event-layout{grid-template-columns:1fr}}
</style>
@endsection
@section('content')
<div class="page-header"><div class="container">
    <h1>{{ Str::limit($event->title, 60) }}</h1>
    <p>{{ $event->category }} &bull; {{ $event->date->format('F j, Y') }}</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; <a href="{{ route('news') }}">News &amp; Events</a> &rsaquo; {{ Str::limit($event->title,40) }}
</div></div>
<section class="content-section"><div class="container">
    <div class="event-layout">
        <div>
            <div class="detail-meta">
                <p><i class="fa fa-calendar me-2" style="color:var(--primary-orange)"></i><strong>Date:</strong> {{ $event->date->format('l, F j, Y') }}</p>
                <p><i class="fa fa-tag me-2" style="color:var(--primary-orange)"></i><strong>Category:</strong> {{ $event->category }}</p>
                <p><i class="fa fa-user me-2" style="color:var(--primary-orange)"></i><strong>Author:</strong> {{ $event->author }}</p>
            </div>
            <div class="detail-full">
                {!! $event->full_description !!}
            </div>
            <div style="margin-top:30px;padding-top:25px;border-top:2px solid var(--gray-200);display:flex;gap:12px;flex-wrap:wrap">
                <a href="{{ route('news') }}" class="cta-button-outline"><i class="fa fa-arrow-left me-2"></i>Back to News</a>
                <a href="{{ config('site.apply.undergraduate') }}" class="cta-button"><i class="fa fa-graduation-cap me-2"></i>Apply Now</a>
            </div>
        </div>
        <div>
            <div class="sidebar-card">
                <h4><i class="fa fa-calendar-days me-2"></i>Quick Links</h4>
                <div style="display:flex;flex-direction:column;gap:8px">
                    <a href="{{ config('site.apply.undergraduate') }}" style="color:var(--primary-green);text-decoration:none;padding:8px 0;border-bottom:1px solid var(--gray-200)"><i class="fa fa-chevron-right fa-xs me-2"></i>Apply for Admission</a>
                    <a href="{{ route('downloads') }}" style="color:var(--primary-green);text-decoration:none;padding:8px 0;border-bottom:1px solid var(--gray-200)"><i class="fa fa-chevron-right fa-xs me-2"></i>Download Forms</a>
                    <a href="{{ route('contact') }}" style="color:var(--primary-green);text-decoration:none;padding:8px 0"><i class="fa fa-chevron-right fa-xs me-2"></i>Contact Us</a>
                </div>
            </div>
            @if($relatedEvents->isNotEmpty())
            <div class="sidebar-card">
                <h4><i class="fa fa-newspaper me-2"></i>Related News</h4>
                @foreach($relatedEvents as $related)
                <div class="related-item">
                    <i class="fa fa-circle-dot fa-xs" style="color:var(--primary-orange);margin-top:4px;flex-shrink:0"></i>
                    <div>
                        <a href="{{ route('news.show',$related) }}">{{ $related->title }}</a>
                        <div class="related-date">{{ $related->date->format('M j, Y') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            <div class="sidebar-card" style="background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff">
                <h4 style="color:var(--light-orange);border-bottom-color:var(--primary-orange)"><i class="fa fa-graduation-cap me-2"></i>Ready to Apply?</h4>
                <p style="font-size:.9rem;margin-bottom:15px;opacity:.9">Applications for the 2026 intake are now open. Secure your place today.</p>
                <a href="{{ config('site.apply.undergraduate') }}" style="background:var(--primary-orange);color:#fff;padding:10px 20px;border-radius:25px;text-decoration:none;font-weight:600;display:inline-block;font-size:.9rem"><i class="fa fa-graduation-cap me-1"></i>Apply Now</a>
            </div>
        </div>
    </div>
</div></section>
@endsection
