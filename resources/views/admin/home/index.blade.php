@extends('layouts.admin')
@section('title', 'Home Page')
@section('page-title', 'Home Page')

@section('styles')
<style>
.home-sections-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px}
.home-section-card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 2px 10px rgba(0,0,0,.06);border-top:4px solid var(--primary-green);display:flex;flex-direction:column;text-decoration:none;color:inherit;transition:transform .2s ease,box-shadow .2s ease;position:relative}
.home-section-card:hover{transform:translateY(-4px);box-shadow:0 10px 24px rgba(0,0,0,.1);border-top-color:var(--primary-orange)}
.home-section-card .num{position:absolute;top:16px;right:18px;font-size:.78rem;font-weight:700;color:var(--gray-600);background:var(--gray-100);border-radius:12px;padding:2px 9px}
.home-section-card .page-icon{width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:14px}
.home-section-card h3{color:var(--primary-green);font-size:1.12rem;margin-bottom:6px}
.home-section-card p{color:var(--gray-600);font-size:.87rem;line-height:1.6;flex:1}
.home-section-card .meta{font-size:.8rem;color:var(--gray-600);margin:12px 0 14px}
.home-section-card .go{font-weight:700;color:var(--primary-orange);font-size:.9rem;display:flex;align-items:center;gap:8px}
.home-section-card.unavailable{opacity:.6;pointer-events:none}
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-house me-2"></i>Home Page Sections</h3>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View Home Page</a>
    </div>
    <p style="color:var(--gray-600);margin-bottom:25px">Choose a section to edit. The cards are in the same order as the sections on the home page, and changes show on the website straight away.</p>
    <div class="home-sections-grid">
        @foreach($sections as $i => $section)
        <a href="{{ $section['url'] }}" class="home-section-card {{ $section['available'] ? '' : 'unavailable' }}">
            <span class="num">{{ $i + 1 }}</span>
            <div class="page-icon"><i class="fa {{ $section['icon'] }}"></i></div>
            <h3>{{ $section['label'] }}</h3>
            <p>{{ $section['description'] }}</p>
            <div class="meta"><i class="fa fa-layer-group me-1"></i> {{ $section['available'] ? $section['meta'] : 'Run the database update (php artisan migrate) to enable' }}</div>
            <span class="go">Edit section <i class="fa fa-arrow-right"></i></span>
        </a>
        @endforeach
    </div>
</div>
@endsection
