<div class="page-header"><div class="container">
    <h1>{{ $page->header_title }}</h1>
    @if($page->header_subtitle)<p>{{ $page->header_subtitle }}</p>@endif
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo;
    @if($page->slug === 'overview')
        About Us &rsaquo; LSUC Overview
    @else
        <a href="{{ route('about') }}">About Us</a> &rsaquo; {{ \App\Models\AboutPage::PAGES[$page->slug]['label'] }}
    @endif
</div></div>
