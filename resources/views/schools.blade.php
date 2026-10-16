@extends('layouts.app')
@section('title', 'Schools & Programs - ' . config('site.name'))
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
</style>
@endsection
@section('content')
<div class="page-header"><div class="container">
    <h1>Schools &amp; Programs</h1>
    <p>Explore our diverse range of industry-aligned academic programs</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; Schools &amp; Programs
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Our Schools</h2>
        <p>Six schools offering programs designed with industry input to ensure graduates are job-ready.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:30px;margin-top:40px">
        {{-- School of Business --}}
        <div class="school-card">
            <div class="school-header">
                <div class="school-icon" style="background:linear-gradient(135deg,#228B22,#1a6b1a)"><i class="fa fa-briefcase"></i></div>
                <h3>School of Business &amp; Management</h3>
            </div>
            <div class="school-body">
                <p>Develop your business acumen with programs designed to meet the demands of Zambia's growing economy.</p>
                <ul class="program-list">
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Business Administration</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Accounting &amp; Finance</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Human Resource Management</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Marketing &amp; Sales</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Certificate in Entrepreneurship</li>
                </ul>
            </div>
            <div class="school-footer">
                <a href="{{ config('site.apply.undergraduate') }}" class="cta-button" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-graduation-cap me-1"></i>Apply</a>
                <button onclick="openPDFViewer('{{ asset('docs/2026 PROGRAMMES.pdf') }}','Programs Catalog')" class="cta-button-outline" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-file-pdf me-1"></i>Programs PDF</button>
            </div>
        </div>
        {{-- School of IT --}}
        <div class="school-card">
            <div class="school-header">
                <div class="school-icon" style="background:linear-gradient(135deg,#ff8c00,#ffb347)"><i class="fa fa-laptop-code"></i></div>
                <h3>School of Information Technology</h3>
            </div>
            <div class="school-body">
                <p>Gain cutting-edge technology skills for the digital economy with our comprehensive IT programs.</p>
                <ul class="program-list">
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Information Technology</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Software Development</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Networking &amp; Cybersecurity</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Certificate in Computer Applications</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Certificate in Web Development</li>
                </ul>
            </div>
            <div class="school-footer">
                <a href="{{ config('site.apply.undergraduate') }}" class="cta-button" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-graduation-cap me-1"></i>Apply</a>
                <button onclick="openPDFViewer('{{ asset('docs/2026 PROGRAMMES.pdf') }}','Programs Catalog')" class="cta-button-outline" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-file-pdf me-1"></i>Programs PDF</button>
            </div>
        </div>
        {{-- School of Engineering --}}
        <div class="school-card">
            <div class="school-header">
                <div class="school-icon" style="background:linear-gradient(135deg,#1565c0,#1976d2)"><i class="fa fa-wrench"></i></div>
                <h3>School of Engineering &amp; Technology</h3>
            </div>
            <div class="school-body">
                <p>Build technical expertise and practical engineering skills demanded by Zambia's infrastructure growth.</p>
                <ul class="program-list">
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Electrical Engineering</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Civil Engineering</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Mechanical Engineering</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Solar &amp; Renewable Energy Technology</li>
                </ul>
            </div>
            <div class="school-footer">
                <a href="{{ config('site.apply.undergraduate') }}" class="cta-button" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-graduation-cap me-1"></i>Apply</a>
                <button onclick="openPDFViewer('{{ asset('docs/2026 PROGRAMMES.pdf') }}','Programs Catalog')" class="cta-button-outline" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-file-pdf me-1"></i>Programs PDF</button>
            </div>
        </div>
        {{-- School of Health Sciences --}}
        <div class="school-card">
            <div class="school-header">
                <div class="school-icon" style="background:linear-gradient(135deg,#c62828,#e53935)"><i class="fa fa-stethoscope"></i></div>
                <h3>School of Health Sciences</h3>
            </div>
            <div class="school-body">
                <p>Prepare for a rewarding career in healthcare with our accredited health sciences programs.</p>
                <ul class="program-list">
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Environmental Health</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Community Health</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Certificate in First Aid &amp; Safety</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Certificate in Health Records Management</li>
                </ul>
            </div>
            <div class="school-footer">
                <a href="{{ config('site.apply.undergraduate') }}" class="cta-button" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-graduation-cap me-1"></i>Apply</a>
                <button onclick="openPDFViewer('{{ asset('docs/2026 PROGRAMMES.pdf') }}','Programs Catalog')" class="cta-button-outline" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-file-pdf me-1"></i>Programs PDF</button>
            </div>
        </div>
        {{-- School of Education --}}
        <div class="school-card">
            <div class="school-header">
                <div class="school-icon" style="background:linear-gradient(135deg,#7b1fa2,#9c27b0)"><i class="fa fa-chalkboard-teacher"></i></div>
                <h3>School of Education</h3>
            </div>
            <div class="school-body">
                <p>Shape the next generation with our education and teacher training programs.</p>
                <ul class="program-list">
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Early Childhood Education</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Diploma in Primary Education</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Certificate in Adult Education</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Certificate in Special Needs Education</li>
                </ul>
            </div>
            <div class="school-footer">
                <a href="{{ config('site.apply.undergraduate') }}" class="cta-button" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-graduation-cap me-1"></i>Apply</a>
                <button onclick="openPDFViewer('{{ asset('docs/2026 PROGRAMMES.pdf') }}','Programs Catalog')" class="cta-button-outline" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-file-pdf me-1"></i>Programs PDF</button>
            </div>
        </div>
        {{-- CPD Short Courses --}}
        <div class="school-card">
            <div class="school-header">
                <div class="school-icon" style="background:linear-gradient(135deg,#f57f17,#fbc02d)"><i class="fa fa-clock-rotate-left"></i></div>
                <h3>CPD &amp; Short Courses</h3>
            </div>
            <div class="school-body">
                <p>Boost your career with our continuing professional development and short courses designed for working professionals.</p>
                <ul class="program-list">
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Digital Marketing &amp; Social Media</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Project Management</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Financial Management &amp; Accounting</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Leadership &amp; Management Skills</li>
                    <li><i class="fa fa-chevron-right fa-xs" style="color:var(--primary-orange)"></i>Web Development &amp; Design</li>
                </ul>
            </div>
            <div class="school-footer">
                <a href="{{ config('site.apply.short_courses') }}" class="cta-button" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-graduation-cap me-1"></i>Enroll</a>
                <button onclick="openPDFViewer('{{ asset('docs/cpd-short-courses.pdf') }}','CPD Short Courses')" class="cta-button-outline" style="font-size:.9rem;padding:10px 20px"><i class="fa fa-file-pdf me-1"></i>Course Info</button>
            </div>
        </div>
    </div>
    <div style="text-align:center;margin-top:50px">
        <button onclick="openPDFViewer('{{ asset('docs/2026 PROGRAMMES.pdf') }}','2026 Programs Catalog')" class="cta-button">
            <i class="fa fa-file-pdf me-2"></i>View Full Programs Catalog (PDF)
        </button>
    </div>
</div></section>
@endsection
