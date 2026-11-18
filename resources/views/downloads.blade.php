@extends('layouts.app')
@section('title', 'Downloads - ' . config('site.name'))
@section('styles')
<style>
.downloads-table-wrap{background:#fff;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.08);overflow:hidden}
.downloads-table{width:100%;border-collapse:collapse}
.downloads-table th{background:var(--primary-green);color:#fff;text-align:left;padding:15px 20px;font-size:.92rem;font-weight:700}
.downloads-table td{padding:15px 20px;border-bottom:1px solid var(--gray-200);vertical-align:middle;font-size:.95rem}
.downloads-table tbody tr:last-child td{border-bottom:none}
.downloads-table tbody tr:hover td{background:#f4faf4}
.doc-name{display:flex;align-items:center;gap:12px;font-weight:600;color:var(--gray-800)}
.doc-name i{color:#d32f2f;font-size:1.4rem;flex-shrink:0}
.doc-desc{color:var(--gray-600);line-height:1.6}
.doc-actions{display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
.doc-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:50px;font-size:.85rem;font-weight:600;text-decoration:none;cursor:pointer;transition:all .3s ease;border:2px solid var(--primary-green);font-family:inherit;white-space:nowrap}
.doc-btn-view{background:transparent;color:var(--primary-green)}
.doc-btn-view:hover{background:var(--primary-green);color:#fff}
.doc-btn-download{background:var(--primary-orange);border-color:var(--primary-orange);color:#fff}
.doc-btn-download:hover{background:var(--light-orange);border-color:var(--light-orange);color:#fff}
@media(max-width:768px){
    .downloads-table thead{display:none}
    .downloads-table,.downloads-table tbody,.downloads-table tr,.downloads-table td{display:block;width:100%}
    .downloads-table tr{padding:16px 18px;border-bottom:1px solid var(--gray-200)}
    .downloads-table tbody tr:last-child{border-bottom:none}
    .downloads-table td{padding:4px 0;border:none}
    .downloads-table tbody tr:hover td{background:transparent}
    .doc-desc{margin:4px 0 10px}
    .doc-actions{justify-content:flex-start}
}
</style>
@endsection
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
    <div class="downloads-table-wrap">
        <table class="downloads-table">
            <thead>
                <tr><th style="width:34%">Document Name</th><th>Description</th><th style="width:230px;text-align:center">Action</th></tr>
            </thead>
            <tbody>
                @foreach($docs as $doc)
                <tr>
                    <td data-label="Document">
                        <div class="doc-name"><i class="fa fa-file-pdf"></i><span>{{ $doc->title }}</span></div>
                    </td>
                    <td data-label="Description" class="doc-desc">{{ $doc->description ?: '—' }}</td>
                    <td data-label="Action">
                        <div class="doc-actions">
                            <button type="button" onclick="openPDFViewer(@js(asset('docs/'.$doc->filename)), @js($doc->title))" class="doc-btn doc-btn-view">
                                <i class="fa fa-eye"></i> View
                            </button>
                            <a href="{{ asset('docs/'.$doc->filename) }}" download class="doc-btn doc-btn-download">
                                <i class="fa fa-download"></i> Download
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
    @if($downloads->isEmpty())
    <p style="text-align:center;color:var(--gray-600);padding:50px">No downloads available. Check back soon.</p>
    @endif
</div></section>
@endsection
