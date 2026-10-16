@extends('layouts.app')
@section('title', 'Downloads - ' . config('site.name'))
@section('content')
<div class="page-header"><div class="container">
    <h1>Downloads</h1>
    <p>Access and download important documents, forms, and resources</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; Downloads
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Document Library</h2>
        <p>Download important forms, guides, and institutional documents.</p>
    </div>
    @foreach($downloads as $category => $docs)
    <h3 style="color:var(--primary-green);font-size:1.3rem;margin:40px 0 20px;border-left:4px solid var(--primary-orange);padding-left:15px">
        <i class="fa fa-folder-open me-2"></i>{{ $category }}
    </h3>
    <div class="cards-grid">
        @foreach($docs as $doc)
        <div class="card">
            <div class="card-icon"><i class="fa fa-file-pdf" style="color:#f44336"></i></div>
            <h3>{{ $doc->title }}</h3>
            <p>{{ $doc->description }}</p>
            <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap">
                <button onclick="openPDFViewer('{{ asset('docs/'.$doc->filename) }}','{{ $doc->title }}')" class="cta-button" style="font-size:.9rem;padding:10px 18px">
                    <i class="fa fa-eye me-1"></i> View
                </button>
                <a href="{{ asset('docs/'.$doc->filename) }}" download class="cta-button-outline" style="font-size:.9rem;padding:10px 18px">
                    <i class="fa fa-download me-1"></i> Download
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
    @if($downloads->isEmpty())
    <p style="text-align:center;color:var(--gray-600);padding:50px">No downloads available. Check back soon.</p>
    @endif
</div></section>
@endsection
