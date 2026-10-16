@extends('layouts.app')
@section('title', 'News & Events - ' . config('site.name'))
@section('styles')
<style>
.news-controls{display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px}
.filter-buttons{display:flex;gap:10px;flex-wrap:wrap}
.filter-btn{padding:9px 20px;border:2px solid var(--primary-green);background:#fff;color:var(--primary-green);border-radius:25px;cursor:pointer;transition:all .3s ease;font-weight:600;font-size:.9rem;text-decoration:none}
.filter-btn:hover,.filter-btn.active{background:var(--primary-green);color:#fff}
.category-badge{display:inline-block;padding:3px 12px;border-radius:15px;font-size:.78rem;font-weight:600}
.badge-news{background:#e3f2fd;color:#1565c0}
.badge-upcoming{background:#e8f5e9;color:#2e7d32}
.badge-past{background:#fce4ec;color:#b71c1c}
.badge-job{background:#fff3e0;color:#e65100}
</style>
@endsection
@section('content')
<div class="page-header"><div class="container">
    <h1>News &amp; Events</h1>
    <p>Stay up to date with the latest happenings at {{ config('site.short_name') }}</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; News &amp; Events
</div></div>
<section class="content-section"><div class="container">
    <form method="GET" action="{{ route('news') }}">
        <div class="news-controls">
            <div class="filter-buttons">
                <a href="{{ route('news') }}" class="filter-btn {{ $filter==='all'?'active':'' }}"><i class="fa fa-border-all me-1"></i>All</a>
                <a href="{{ route('news',['filter'=>'Latest News']) }}" class="filter-btn {{ $filter==='Latest News'?'active':'' }}"><i class="fa fa-newspaper me-1"></i>Latest News</a>
                <a href="{{ route('news',['filter'=>'Upcoming Event']) }}" class="filter-btn {{ $filter==='Upcoming Event'?'active':'' }}"><i class="fa fa-calendar me-1"></i>Upcoming</a>
                <a href="{{ route('news',['filter'=>'Past Event']) }}" class="filter-btn {{ $filter==='Past Event'?'active':'' }}"><i class="fa fa-clock-rotate-left me-1"></i>Past Events</a>
            </div>
            <div style="display:flex;gap:10px">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search news..." style="padding:9px 15px;border:2px solid var(--gray-200);border-radius:25px;font-size:.9rem;width:220px">
                <button type="submit" style="padding:9px 20px;background:var(--primary-orange);color:#fff;border:none;border-radius:25px;cursor:pointer;font-weight:600"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>

    @if($latestNews->isNotEmpty())
    <div class="section-title" style="text-align:left;margin-bottom:25px">
        <h2 style="font-size:1.8rem"><i class="fa fa-newspaper me-2" style="color:var(--primary-orange)"></i>Latest News</h2>
    </div>
    <div class="updates-grid">
        @foreach($latestNews as $event)
        <div class="update-card">
            <div class="update-image"><i class="fa fa-newspaper fa-3x"></i></div>
            <div class="update-content">
                <span class="category-badge badge-news">{{ $event->category }}</span>
                <div class="update-date" style="margin-top:8px"><i class="fa fa-calendar me-1"></i>{{ $event->date->format('F j, Y') }}</div>
                <h3>{{ $event->title }}</h3>
                <p>{{ Str::limit($event->short_description, 130) }}</p>
                <a href="{{ route('news.show',$event) }}" class="cta-button-outline" style="margin-top:12px;display:inline-block;font-size:.9rem">Read More <i class="fa fa-arrow-right ms-1"></i></a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($upcomingEvents->isNotEmpty())
    <div class="section-title" style="text-align:left;margin:50px 0 25px">
        <h2 style="font-size:1.8rem"><i class="fa fa-calendar me-2" style="color:var(--primary-orange)"></i>Upcoming Events</h2>
    </div>
    <div class="updates-grid">
        @foreach($upcomingEvents as $event)
        <div class="update-card">
            <div class="update-image" style="background:linear-gradient(135deg,#ff8c00,#ffb347)"><i class="fa fa-calendar-days fa-3x"></i></div>
            <div class="update-content">
                <span class="category-badge badge-upcoming">{{ $event->category }}</span>
                <div class="update-date" style="margin-top:8px"><i class="fa fa-calendar me-1"></i>{{ $event->date->format('F j, Y') }}</div>
                <h3>{{ $event->title }}</h3>
                <p>{{ Str::limit($event->short_description, 130) }}</p>
                <a href="{{ route('news.show',$event) }}" class="cta-button-outline" style="margin-top:12px;display:inline-block;font-size:.9rem">Read More <i class="fa fa-arrow-right ms-1"></i></a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($pastEvents->isNotEmpty())
    <div class="section-title" style="text-align:left;margin:50px 0 25px">
        <h2 style="font-size:1.8rem"><i class="fa fa-clock-rotate-left me-2" style="color:var(--gray-600)"></i>Past Events</h2>
    </div>
    <div class="updates-grid">
        @foreach($pastEvents as $event)
        <div class="update-card" style="opacity:.85">
            <div class="update-image" style="background:linear-gradient(135deg,#757575,#9e9e9e)"><i class="fa fa-calendar-check fa-3x"></i></div>
            <div class="update-content">
                <span class="category-badge badge-past">{{ $event->category }}</span>
                <div class="update-date" style="margin-top:8px"><i class="fa fa-calendar me-1"></i>{{ $event->date->format('F j, Y') }}</div>
                <h3>{{ $event->title }}</h3>
                <p>{{ Str::limit($event->short_description, 130) }}</p>
                <a href="{{ route('news.show',$event) }}" class="cta-button-outline" style="margin-top:12px;display:inline-block;font-size:.9rem">Read More <i class="fa fa-arrow-right ms-1"></i></a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($events->isEmpty())
    <div style="text-align:center;padding:80px 20px;background:var(--gray-100);border-radius:15px">
        <i class="fa fa-newspaper fa-4x" style="color:var(--gray-200);margin-bottom:20px"></i>
        <h3 style="color:var(--gray-600)">No results found</h3>
        <p style="color:var(--gray-600)">Try adjusting your search or filter criteria.</p>
        <a href="{{ route('news') }}" class="cta-button" style="margin-top:20px;display:inline-block">View All News</a>
    </div>
    @endif
</div></section>
@endsection
