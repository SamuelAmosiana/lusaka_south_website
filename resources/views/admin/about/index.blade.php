@extends('layouts.admin')
@section('title', 'About Pages')
@section('page-title', 'About Pages')

@section('styles')
<style>
.about-pages-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px}
.about-page-card{background:#fff;border-radius:12px;padding:25px;box-shadow:0 2px 10px rgba(0,0,0,.06);border-top:4px solid var(--primary-green);display:flex;flex-direction:column}
.about-page-card .page-icon{width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:15px}
.about-page-card h3{color:var(--primary-green);font-size:1.15rem;margin-bottom:6px}
.about-page-card p{color:var(--gray-600);font-size:.88rem;line-height:1.6;flex:1}
.about-page-card .meta{font-size:.82rem;color:var(--gray-600);margin:12px 0 18px}
.about-page-card .actions{display:flex;gap:10px;flex-wrap:wrap}
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-building-columns me-2"></i>Manage the About Us pages</h3>
    </div>
    <p style="color:var(--gray-600);margin-bottom:25px">Choose a page to edit its headings, cards and people. Changes show on the website straight away.</p>
    <div class="about-pages-grid">
        @foreach(\App\Models\AboutPage::PAGES as $slug => $config)
            @php $page = $pages->get($slug); @endphp
            <div class="about-page-card">
                <div class="page-icon"><i class="fa {{ $config['icon'] }}"></i></div>
                <h3>{{ $config['label'] }}</h3>
                <p>{{ $page?->header_subtitle }}</p>
                <div class="meta">
                    <i class="fa fa-layer-group me-1"></i> {{ $page?->items_count ?? 0 }} {{ Str::plural('item', $page?->items_count ?? 0) }}
                    &middot; {{ implode(', ', array_column($config['groups'], 'label')) }}
                </div>
                <div class="actions">
                    @if($page)
                    <a href="{{ route('admin.about.edit', $page) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Edit Page</a>
                    @endif
                    <a href="{{ route($config['route']) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
