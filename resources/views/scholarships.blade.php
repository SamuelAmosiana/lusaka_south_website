@extends('layouts.app')
@section('title', 'Scholarships - ' . config('site.name'))
@section('description', 'Former Presidents of Zambia Scholarship Scheme at Lusaka South University College — seven tiered awards from 20% to 100% tuition discounts for the 2026/2027 academic cycle.')
@section('styles')
<style>
.sch-intro{max-width:880px;margin:0 auto;text-align:center}
.sch-eyebrow{display:inline-block;color:var(--primary-orange);font-weight:700;letter-spacing:.5px;text-transform:uppercase;font-size:.9rem;margin-bottom:10px}
.sch-intro h2{color:var(--primary-green);font-size:2.3rem;line-height:1.25;margin-bottom:18px}
.sch-intro p{color:var(--gray-600);font-size:1.08rem;line-height:1.8}
.sch-btns{display:flex;gap:15px;justify-content:center;flex-wrap:wrap;margin-top:30px}
.sch-btns .cta-button,.sch-btns .cta-button-outline{display:inline-flex;align-items:center;gap:8px}
.sch-alt{background:var(--gray-100)}
.sch-table-wrap{overflow-x:auto;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.08);background:#fff}
.sch-table{width:100%;border-collapse:collapse;min-width:760px}
.sch-table th{background:var(--primary-green);color:#fff;text-align:left;padding:16px 18px;font-size:.92rem;font-weight:700}
.sch-table td{padding:16px 18px;border-bottom:1px solid var(--gray-200);color:var(--gray-800);font-size:.93rem;line-height:1.6;vertical-align:top}
.sch-table tr:last-child td{border-bottom:none}
.sch-table tr:hover td{background:#f4faf4}
.sch-table td:first-child{font-weight:700;color:var(--primary-green)}
.sch-table .award-name{font-weight:700;color:var(--gray-800)}
.sch-pill{display:inline-block;background:var(--primary-orange);color:#fff;font-weight:800;padding:4px 12px;border-radius:20px;font-size:.9rem}
.sch-center{text-align:center;margin-top:35px}
.award-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:28px}
.award-card{background:#fff;border-radius:15px;padding:28px;box-shadow:0 10px 30px rgba(0,0,0,.1);border:2px solid transparent;border-top:5px solid var(--primary-green);transition:all .3s ease;display:flex;flex-direction:column}
.award-card:hover{transform:translateY(-8px);border-color:var(--primary-orange);box-shadow:0 20px 40px rgba(0,0,0,.15)}
.award-head{display:flex;align-items:center;gap:16px;margin-bottom:16px}
.award-value{width:76px;height:76px;border-radius:50%;background:linear-gradient(135deg,var(--primary-orange),var(--light-orange));color:#fff;font-weight:800;font-size:1.35rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.award-head h3{color:var(--primary-green);font-size:1.2rem;margin-bottom:4px}
.award-head small{color:var(--gray-600);font-weight:600}
.award-card p{color:var(--gray-600);line-height:1.75;flex:1}
.award-meta{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
.award-meta span{background:var(--gray-100);color:var(--primary-green);font-size:.82rem;font-weight:700;padding:6px 12px;border-radius:20px;display:inline-flex;align-items:center;gap:6px}
.award-card-featured{grid-column:1/-1;background:linear-gradient(135deg,var(--dark-green),var(--primary-green));border-top-color:var(--primary-orange)}
.award-card-featured .award-head h3,.award-card-featured .award-head small,.award-card-featured p{color:#fff}
.award-card-featured .award-meta span{background:rgba(255,255,255,.15);color:#fff}
.notes-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:25px}
.note-card{background:#fff;border-radius:15px;padding:26px;box-shadow:0 10px 30px rgba(0,0,0,.08);border-left:4px solid var(--primary-orange)}
.note-num{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;font-weight:800;display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.note-card h3{color:var(--primary-green);font-size:1.12rem;margin-bottom:10px}
.note-card p{color:var(--gray-600);line-height:1.75;margin:0;font-size:.95rem}
.essence-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:28px}
.essence-card{display:flex;gap:18px;align-items:flex-start;background:#fff;border-radius:15px;padding:26px;box-shadow:0 10px 30px rgba(0,0,0,.08);transition:all .3s ease}
.essence-card:hover{transform:translateY(-6px);box-shadow:0 18px 36px rgba(0,0,0,.13)}
.essence-icon{width:54px;height:54px;border-radius:14px;background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.35rem;flex-shrink:0}
.essence-card h3{color:var(--primary-green);font-size:1.15rem;margin-bottom:10px}
.essence-card p{color:var(--gray-600);line-height:1.8;margin:0;font-size:.95rem}
.sch-cta{background:linear-gradient(135deg,var(--dark-green),var(--primary-green));color:#fff;text-align:center;padding:70px 0}
.sch-cta h2{font-size:2.2rem;margin-bottom:12px}
.sch-cta p{font-size:1.1rem;opacity:.9}
.sch-cta .cta-button-outline{color:#fff;border-color:#fff}
.sch-cta .cta-button-outline:hover{background:#fff;color:var(--primary-green)}
@media(max-width:768px){.sch-intro h2,.sch-cta h2{font-size:1.75rem}.essence-grid{grid-template-columns:1fr}.award-grid{grid-template-columns:1fr}}
</style>
@endsection
@section('content')
@php
    $pdf = 'docs/scholarships.pdf';
    $hasPdf = file_exists(public_path($pdf));
    $applyUrl = config('site.apply.scholarships');
@endphp
<div class="page-header"><div class="container">
    <h1>Scholarships</h1>
    <p>Former Presidents of Zambia Scholarship Scheme — 2026/2027 Academic Cycle</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; Scholarships
</div></div>

{{-- Intro --}}
<section class="content-section"><div class="container">
    <div class="sch-intro">
        <span class="sch-eyebrow">{{ config('site.name') }} ({{ config('site.short_name') }})</span>
        <h2>Former Presidents of Zambia Scholarship Awards</h2>
        <p>{{ config('site.short_name') }} is pleased to announce the eligibility criteria for the Former Presidents of Zambia Scholarship Scheme for the 2026/2027 academic cycle, funded by the University and its cooperating partners.</p>
        <div class="sch-btns">
            <a href="#awards" class="cta-button-outline"><i class="fa fa-compass"></i> Explore Awards</a>
            @if($hasPdf)
            <button type="button" onclick="openPDFViewer('{{ asset($pdf) }}','Scholarship Summary')" class="cta-button-outline" style="cursor:pointer"><i class="fa fa-file-pdf"></i> View &amp; Download PDF</button>
            @endif
            <a href="{{ $applyUrl }}" target="_blank" rel="noopener" class="cta-button"><i class="fa fa-paper-plane"></i> Apply Now</a>
        </div>
    </div>
</div></section>

{{-- At a glance --}}
<section class="content-section sch-alt"><div class="container">
    <div class="section-title"><h2>Scholarship Awards at a Glance</h2>
        <p>Seven tiered awards covering rural, urban, international, and distance learning students</p>
    </div>
    <div class="sch-table-wrap">
        <table class="sch-table">
            <thead><tr><th>#</th><th>Award Name</th><th>Award Value</th><th>Eligibility Criteria</th><th>Slots per Intake</th></tr></thead>
            <tbody>
                @foreach([
                    ['Kenneth D. Kaunda Scholarship Award', '70%', 'Applicants from Rural areas and Differently Abled individuals with minimum 5 Distinctions in the O’ Level School Certificate (G12)', '50'],
                    ['Frederick T. Chiluba Scholarship Award', '60%', 'Applicants from Rural, Peri-Rural, and Urban areas; Differently Abled individuals with minimum 5 Merits in the O’ Level School Certificate (G12)', '50'],
                    ['Levy P. Mwanawasa Scholarship Award', '50%', 'Open to all Zambian Citizens with minimum 3 Merits in the O’ Level School Certificate (G12)', '50'],
                    ['Rupiah B. Banda Scholarship Award', '40%', 'Applicable to Technical Skills Programmes only with 5 Credits or Better', '50'],
                    ['Michael C. Sata Scholarship Award', '30%', 'Applicable to Distance Learning Programmes and Skills Courses only', '50'],
                    ['Edgar C. Lungu Scholarship Award', '20%', 'International Applicants (Non-Zambians) only', '50'],
                    ['Hakainde Hichilema CDF Scholarship Award', '100%', 'CDF Scholarships for Skills Courses supported by the Government. Once accepted, applicants apply at the local authority through the Councillor’s Ward Development Committee Office in their constituency', 'N/A'],
                ] as $i => [$name, $value, $criteria, $slots])
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="award-name">{{ $name }}</td>
                    <td><span class="sch-pill">{{ $value }}</span></td>
                    <td>{{ $criteria }}</td>
                    <td>{{ $slots }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="sch-center">
        <a href="{{ $applyUrl }}" target="_blank" rel="noopener" class="cta-button"><i class="fa fa-paper-plane" style="margin-right:8px"></i>Apply Now</a>
    </div>
</div></section>

{{-- Each award --}}
<section class="content-section" id="awards" style="scroll-margin-top:90px"><div class="container">
    <div class="section-title"><h2>Explore Each Award</h2>
        <p>Seven distinguished scholarships named after Zambia’s former Presidents</p>
    </div>
    <div class="award-grid">
        @foreach([
            ['70%', 'Kenneth D. Kaunda Award', 'Rural & Differently Abled', 'Highest honor for founding father’s vision of free education for all. Requires minimum 5 Distinctions at O’ Level (G12).', ['fa-users' => '50 Slots / Intake', 'fa-tree' => 'Rural Areas']],
            ['60%', 'Frederick T. Chiluba Award', 'Rural, Peri-Rural & Urban', 'Open to rural, peri-rural, and urban applicants including Differently Abled individuals. Requires minimum 5 Merits at O’ Level (G12).', ['fa-users' => '50 Slots / Intake', 'fa-map-location-dot' => 'All Areas']],
            ['50%', 'Levy P. Mwanawasa Award', 'All Zambian Citizens', 'Open to all Zambian citizens. Requires minimum 3 Merits at O’ Level School Certificate (G12).', ['fa-users' => '50 Slots / Intake', 'fa-flag' => 'Zambians Only']],
            ['40%', 'Rupiah B. Banda Award', 'Technical Skills Only', 'Strictly for Technical Skills Programmes. Applicants must have 5 Credits or Better at O’ Level.', ['fa-users' => '50 Slots / Intake', 'fa-screwdriver-wrench' => 'Technical Only']],
            ['30%', 'Michael C. Sata Award', 'Distance Learning & Skills', 'For Distance Learning (ODL) and Skills Courses only. Designed to support flexible learning pathways.', ['fa-users' => '50 Slots / Intake', 'fa-laptop' => 'ODL & Skills']],
            ['20%', 'Edgar C. Lungu Award', 'International Students', 'Reserved for International (Non-Zambian) applicants. Promotes regional integration and continental education access.', ['fa-users' => '50 Slots / Intake', 'fa-earth-africa' => 'Non-Zambians']],
        ] as [$value, $name, $group, $desc, $meta])
        <div class="award-card">
            <div class="award-head">
                <div class="award-value">{{ $value }}</div>
                <div><h3>{{ $name }}</h3><small>{{ $group }}</small></div>
            </div>
            <p>{{ $desc }}</p>
            <div class="award-meta">
                @foreach($meta as $icon => $label)<span><i class="fa {{ $icon }}"></i> {{ $label }}</span>@endforeach
            </div>
        </div>
        @endforeach
        <div class="award-card award-card-featured">
            <div class="award-head">
                <div class="award-value">100%</div>
                <div><h3>Hakainde Hichilema CDF Scholarship Award</h3><small>Government-Funded &bull; Full Scholarship</small></div>
            </div>
            <p>100% Government-supported CDF Scholarship for Skills Courses. Once accepted, students obtain an acceptance letter and submit it to the local Council through the Ward Development Committee Office in their constituency. Councillors facilitate the process at the local authority level.</p>
            <div class="award-meta">
                <span><i class="fa fa-landmark"></i> Govt. of Zambia</span>
                <span><i class="fa fa-toolbox"></i> Skills Courses</span>
            </div>
        </div>
    </div>
</div></section>

{{-- Application notes --}}
<section class="content-section sch-alt"><div class="container">
    <div class="section-title"><h2>Application Notes</h2>
        <p>Important requirements and conditions for each scholarship award</p>
    </div>
    <div class="notes-grid">
        @foreach([
            ['Supporting Documentation', 'Applicants for the Kaunda and Chiluba awards must provide proof of residence (Chief’s / Headman / Headwoman letter or Utility bill) and a Zambia Association with Disability (ZAD) card for disability status.'],
            ['Programme Specifics', 'The Rupiah Banda award is strictly for trade and technical certificates, while the Sata award is for Open and Distance Learning (ODL) mode and Skills Awards only.'],
            ['International Students', 'The Edgar Lungu award is designed to promote regional integration and is reserved for students outside Zambia.'],
            ['Hakainde Hichilema CDF Award', 'The 100% Award is for skills programmes supported by the Government of the Republic of Zambia. Students receive an acceptance letter with full fees and submit it to the Council through the Ward Development Committee Office.'],
        ] as $i => [$title, $text])
        <div class="note-card">
            <div class="note-num">{{ $i + 1 }}</div>
            <h3>{{ $title }}</h3>
            <p>{{ $text }}</p>
        </div>
        @endforeach
    </div>
</div></section>

{{-- Essence --}}
<section class="content-section"><div class="container">
    <div class="section-title"><h2>The Essence of {{ config('site.short_name') }}’s Former Presidents Scholarship</h2>
        <p>Scholarship Awards are more than just fee discounts — here’s the real purpose behind them</p>
    </div>
    <div class="essence-grid">
        @foreach([
            ['fa-landmark-flag', 'Honor Zambia’s Legacy Through Education', 'Each scholarship ties LSUC’s mission to the legacy of Zambia’s former Presidents. From KK’s “One Zambia, One Nation” to later presidents’ focus on economic growth, we say: “Education builds the Zambia they envisioned.” It connects students to national history and pride.'],
            ['fa-door-open', 'Make Quality Education Accessible', 'With discounts from 20% to 70%, we remove financial barriers. A 70% KK Award can cut a K10,000 tuition to just K3,000 — the difference between a youth staying home vs. becoming a skilled graduate. It’s practical empowerment, not just charity.'],
            ['fa-medal', 'Reward Merit + Need + National Service', 'The tiered percentage reflects contribution: the 70% KK Award is the highest honor for the founding father’s vision, while the 20% ECL Award recognizes recent efforts to expand universities. Students choose an award that resonates with them, while LSUC supports 1,200 students across both intakes.'],
            ['fa-star', 'Build LSUC’s Brand as “Zambia’s Premium Institution”', 'Naming awards after presidents positions LSUC as a national institution, not just another college. It signals stability, patriotism, and alignment with Zambia’s development agenda. Parents trust it. Students feel proud.'],
        ] as [$icon, $title, $text])
        <div class="essence-card">
            <div class="essence-icon"><i class="fa {{ $icon }}"></i></div>
            <div><h3>{{ $title }}</h3><p>{{ $text }}</p></div>
        </div>
        @endforeach
    </div>
</div></section>

{{-- CTA --}}
<section class="sch-cta"><div class="container">
    <h2>Ready to Apply for a Scholarship?</h2>
    <p>Download the full scholarship summary or start your application today</p>
    <div class="sch-btns">
        @if($hasPdf)
        <button type="button" onclick="openPDFViewer('{{ asset($pdf) }}','Scholarship Summary')" class="cta-button-outline" style="cursor:pointer;background:transparent"><i class="fa fa-file-pdf"></i> View &amp; Download PDF</button>
        @endif
        <a href="{{ $applyUrl }}" target="_blank" rel="noopener" class="cta-button"><i class="fa fa-paper-plane"></i> Apply Now</a>
    </div>
</div></section>
@endsection
