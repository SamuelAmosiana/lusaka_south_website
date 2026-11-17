@extends('layouts.app')
@section('title', 'Schools & Programmes - ' . config('site.name'))
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
    <h1>Schools &amp; Programmes</h1>
    <p>Explore our diverse range of industry-aligned academic programmes</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; Schools &amp; Programmes
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Our Schools</h2>
        <p>Three schools and a range of trade and skills programmes, designed with industry input to ensure graduates are job-ready.</p>
    </div>
    @php
        $schools = [
            [
                'name' => 'School of Business & Management Studies',
                'icon' => 'fa-briefcase',
                'color' => 'linear-gradient(135deg,#228B22,#1a6b1a)',
                'intro' => "Develop your business acumen with programmes designed to meet the demands of Zambia's growing economy.",
                'apply' => config('site.apply.undergraduate'),
                'programmes' => [
                    ['Bachelor of Business Administration (BBA)', 'Degree'],
                    ['BSc Procurement and Supply Chain Management (BScPSCM)', 'Degree'],
                    ['Bachelor of Accounting and Finance (BAF)', 'Degree'],
                    ['Diploma in Business Administration', 'Diploma'],
                    ['Diploma in Technical & Vocational Teaching (Teaching Methodology)', 'Diploma'],
                ],
            ],
            [
                'name' => 'School of Law',
                'icon' => 'fa-scale-balanced',
                'color' => 'linear-gradient(135deg,#7b1fa2,#9c27b0)',
                'intro' => 'Build a strong foundation in legal principles and practice for a career in law and public service.',
                'apply' => config('site.apply.undergraduate'),
                'programmes' => [
                    ['Bachelor of Laws (LLB)', 'Degree'],
                ],
            ],
            [
                'name' => 'School of Engineering and Information Technology',
                'icon' => 'fa-gears',
                'color' => 'linear-gradient(135deg,#1565c0,#1976d2)',
                'intro' => "Gain the technical and digital skills demanded by Zambia's infrastructure, energy and technology sectors.",
                'apply' => config('site.apply.undergraduate'),
                'programmes' => [
                    ['Bachelor of Science in Information Communication Technology', 'Degree'],
                    ['Diploma in Electrical Engineering and Renewable Energy', 'Diploma'],
                    ['Diploma in Mechanical and Industrial Engineering', 'Diploma'],
                    ['Diploma in Control & Instrumentation Engineering', 'Diploma'],
                ],
            ],
            [
                'name' => 'Trade and Skills Programmes',
                'icon' => 'fa-screwdriver-wrench',
                'color' => 'linear-gradient(135deg,#ff8c00,#ffb347)',
                'intro' => 'Hands-on certificate programmes that equip you with practical, employable skills for the trades and health care.',
                'apply' => config('site.apply.short_courses'),
                'wide' => true,
                'programmes' => [
                    ['Certificate in Automotive Engineering – Level 1', 'Certificate'],
                    ['Certificate in Automotive Engineering – Level 2', 'Certificate'],
                    ['Certificate in Automotive Engineering – Level 3', 'Certificate'],
                    ['Certificate in Electrical Engineering/Electrical Technology – Level 1', 'Certificate'],
                    ['Certificate in Electrical Engineering/Electrical Technology – Level 2', 'Certificate'],
                    ['Certificate in Electrical Engineering/Electrical Technology – Level 3', 'Certificate'],
                    ['Certificate in Solar Technology (Skills Award)', 'Certificate'],
                    ['Certificate in Occupational Safety, Health & Environment', 'Certificate'],
                    ['Certificate in Civil Engineering and Construction Management', 'Certificate'],
                    ['Certificate in Information Communication Technologies (ICT)', 'Certificate'],
                    ['Certificate in Nursing & Health Care Assistant', 'Certificate'],
                    ['Certificate in Psychosocial Counselling', 'Certificate'],
                ],
            ],
        ];
    @endphp
    <div class="schools-grid">
        @foreach($schools as $school)
        <div class="school-card {{ !empty($school['wide']) ? 'school-card-wide' : '' }}">
            <div class="school-header">
                <div class="school-icon" style="background:{{ $school['color'] }}"><i class="fa {{ $school['icon'] }}"></i></div>
                <div>
                    <h3>{{ $school['name'] }}</h3>
                    <small class="school-count">{{ count($school['programmes']) }} {{ Str::plural('programme', count($school['programmes'])) }}</small>
                </div>
            </div>
            <div class="school-body">
                <p>{{ $school['intro'] }}</p>
                <ul class="program-list">
                    @foreach($school['programmes'] as [$programme, $level])
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i><span class="program-name">{{ $programme }}</span><span class="level-tag level-{{ strtolower($level) }}">{{ $level }}</span></li>
                    @endforeach
                </ul>
            </div>
            <div class="school-footer">
                <a href="{{ $school['apply'] }}" class="cta-button" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-graduation-cap" style="margin-right:8px"></i>Apply</a>
                <button onclick="openPDFViewer('{{ asset('docs/2026 PROGRAMMES.pdf') }}','Programmes Catalog')" class="cta-button-outline" style="font-size:.9rem;padding:10px 20px;cursor:pointer"><i class="fa fa-file-pdf" style="margin-right:8px"></i>Programmes PDF</button>
            </div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:50px">
        <button onclick="openPDFViewer('{{ asset('docs/2026 PROGRAMMES.pdf') }}','2026 Programmes Catalog')" class="cta-button">
            <i class="fa fa-file-pdf" style="margin-right:8px"></i>View Full Programmes Catalog (PDF)
        </button>
    </div>
</div></section>
@endsection
