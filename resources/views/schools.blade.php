@extends('layouts.app')
@section('title', $settings->header_title . ' - ' . config('site.name'))
@section('styles')
<style>
.school-card{background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.1);transition:all .3s ease;border:2px solid transparent}
.school-card:hover{transform:translateY(-8px);box-shadow:0 20px 40px rgba(0,0,0,.15);border-color:var(--primary-orange)}
.school-header{padding:25px 25px 15px;display:flex;align-items:center;gap:15px;border-bottom:2px solid var(--gray-100)}
.school-icon{width:55px;height:55px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;flex-shrink:0}
.school-header h3{color:var(--primary-green);font-size:1.15rem;margin:0}
.school-body{padding:20px 25px 25px}
.school-body p{color:var(--gray-600);margin-bottom:15px;font-size:.95rem}
.program-list{list-style:none;margin-bottom:20px}
.program-list li{padding:6px 0;color:var(--gray-800);font-size:.9rem;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;gap:8px}
.program-list li:last-child{border-bottom:none}
.school-footer{padding:0 25px 25px;display:flex;gap:10px;flex-wrap:wrap}
.schools-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:30px;margin-top:40px}
.school-card-wide{grid-column:1/-1}
.school-card-wide .program-list{display:grid;grid-template-columns:repeat(2,1fr);column-gap:30px}
.school-card-wide .program-list li:last-child{border-bottom:1px solid var(--gray-100)}
.school-count{color:var(--gray-600);font-weight:600;font-size:.85rem}
.program-name{flex:1}
.level-tag{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;padding:2px 9px;border-radius:12px;flex-shrink:0}
.level-degree{background:#e8f5e9;color:var(--primary-green)}
.level-diploma{background:#e3f2fd;color:#1565c0}
.level-certificate{background:#fff3e0;color:#e65100}
@media(max-width:768px){.schools-grid{grid-template-columns:1fr}.school-card-wide .program-list{grid-template-columns:1fr}}
</style>
@endsection
@section('content')
<div class="page-header"><div class="container">
    <h1>{{ $settings->header_title }}</h1>
    @if($settings->header_subtitle)<p>{{ $settings->header_subtitle }}</p>@endif
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; {{ $settings->header_title }}
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>{{ $settings->section_title }}</h2>
        @if($settings->section_subtitle)<p>{{ $settings->section_subtitle }}</p>@endif
    </div>
    <div class="schools-grid">
        @foreach($schools as $school)
        @php $count = $school->activeProgrammes->count(); @endphp
        <div class="school-card {{ $count >= \App\Models\School::WIDE_FROM ? 'school-card-wide' : '' }}">
            <div class="school-header">
                <div class="school-icon" style="background:linear-gradient(135deg,var(--primary-green),var(--dark-green))"><i class="fa {{ $school->icon }}"></i></div>
                <div>
                    <h3>{{ $school->name }}</h3>
                    <small class="school-count">{{ $count }} {{ Str::plural('programme', $count) }}</small>
                </div>
            </div>
            <div class="school-body">
                @if($school->intro)<p>{{ $school->intro }}</p>@endif
                <ul class="program-list">
                    @foreach($school->activeProgrammes as $programme)
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i><span class="program-name">{{ $programme->name }}</span><span class="level-tag level-{{ strtolower($programme->level) }}">{{ $programme->level }}</span></li>
                    @endforeach
                </ul>
            </div>
            <div class="school-footer">
                <a href="{{ $school->apply_url }}" class="cta-button" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-graduation-cap" style="margin-right:8px"></i>Apply</a>
                @if($settings->catalog_url)
                <button type="button" onclick="openPDFViewer(@js($settings->catalog_url), 'Programmes Catalog')" class="cta-button-outline" style="font-size:.9rem;padding:10px 20px;cursor:pointer"><i class="fa fa-file-pdf" style="margin-right:8px"></i>Programmes PDF</button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @if($schools->isEmpty())
    <p style="text-align:center;color:var(--gray-600);padding:50px">Programme information is being updated. Please check back soon.</p>
    @endif
    @if($settings->catalog_url)
    <div style="text-align:center;margin-top:50px">
        <button type="button" onclick="openPDFViewer(@js($settings->catalog_url), 'Programmes Catalog')" class="cta-button">
            <i class="fa fa-file-pdf" style="margin-right:8px"></i>View Full Programmes Catalog (PDF)
        </button>
    </div>
    @endif
</div></section>
@endsection
