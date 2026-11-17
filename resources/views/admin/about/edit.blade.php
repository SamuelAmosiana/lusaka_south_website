@extends('layouts.admin')
@php $config = $page->config(); @endphp
@section('title', 'Edit ' . $config['label'])
@section('page-title', 'About Pages: ' . $config['label'])

@section('styles')
<style>
.item-thumb{width:46px;height:46px;border-radius:50%;object-fit:cover;border:2px solid var(--primary-orange)}
.item-icon{width:40px;height:40px;border-radius:8px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:var(--primary-green)}
.item-placeholder{width:46px;height:46px;border-radius:50%;background:var(--gray-200);display:flex;align-items:center;justify-content:center;color:var(--gray-600)}
tr.inactive td{opacity:.55}
.table-wrap{overflow-x:auto}
</style>
@endsection

@section('content')
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('admin.about.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> All About Pages</a>
    <a href="{{ route($config['route']) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View Page on Website</a>
</div>

{{-- Page text --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-heading me-2"></i>Page Headings &amp; Text</h3>
    </div>

    @if($errors->any())
    <div class="alert-error">
        <ul style="margin-left:20px">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.about.update', $page) }}">
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="form-group">
                <label for="header_title">Banner Title *</label>
                <input type="text" name="header_title" id="header_title" class="form-control" value="{{ old('header_title', $page->header_title) }}" required>
            </div>
            <div class="form-group">
                <label for="header_subtitle">Banner Subtitle</label>
                <input type="text" name="header_subtitle" id="header_subtitle" class="form-control" value="{{ old('header_subtitle', $page->header_subtitle) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="section_title">Section Heading *</label>
                <input type="text" name="section_title" id="section_title" class="form-control" value="{{ old('section_title', $page->section_title) }}" required>
            </div>
            <div class="form-group">
                <label for="section_subtitle">Section Subtitle</label>
                <input type="text" name="section_subtitle" id="section_subtitle" class="form-control" value="{{ old('section_subtitle', $page->section_subtitle) }}">
            </div>
        </div>
        @if($config['extra'])
        <h4 style="color:var(--primary-green);margin:10px 0 15px;font-size:1rem"><i class="fa fa-square-plus me-1"></i> {{ $config['extra']['label'] }}</h4>
        <div class="form-group">
            <label for="extra_title">{{ $config['extra']['title'] }}</label>
            <input type="text" name="extra_title" id="extra_title" class="form-control" value="{{ old('extra_title', $page->extra_title) }}">
        </div>
        <div class="form-group">
            <label for="extra_text">{{ $config['extra']['text'] }}</label>
            <textarea name="extra_text" id="extra_text" class="form-control" rows="3">{{ old('extra_text', $page->extra_text) }}</textarea>
        </div>
        @endif
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Page Text</button>
    </form>
</div>

{{-- Items, one table per group --}}
<form method="POST" action="{{ route('admin.about.batch', $page) }}" id="batch-form">
    @csrf
</form>

@foreach($config['groups'] as $group => $groupConfig)
@php
    $items = $page->items->where('group', $group);
    $isPerson = $groupConfig['type'] === 'person';
@endphp
<div class="card">
    <div class="card-header">
        <h3><i class="fa {{ $isPerson ? 'fa-user-tie' : 'fa-table-cells-large' }} me-2"></i>{{ $groupConfig['label'] }}</h3>
        <a href="{{ route('admin.about.items.create', [$page, $group]) }}" class="btn btn-orange btn-sm"><i class="fa fa-plus"></i> Add {{ $isPerson ? 'Person' : 'Card' }}</a>
    </div>
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:80px">Order</th>
                <th style="width:70px">{{ $isPerson ? 'Photo' : 'Icon' }}</th>
                <th>{{ $isPerson ? 'Name / Role' : 'Title / Text' }}</th>
                <th style="width:80px;text-align:center">Show</th>
                <th style="width:110px;text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr class="{{ $item->is_active ? '' : 'inactive' }}">
                <td><input type="number" form="batch-form" name="items[{{ $item->id }}][sort_order]" value="{{ $item->sort_order }}" class="form-control" style="padding:6px 8px;text-align:center" min="0"></td>
                <td>
                    @if($isPerson)
                        @if($item->image_url)<img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="item-thumb">@else<div class="item-placeholder"><i class="fa fa-user"></i></div>@endif
                    @else
                        <div class="item-icon"><i class="fa {{ $item->icon }}"></i></div>
                    @endif
                </td>
                <td>
                    <strong>{{ $item->title }}</strong>
                    @if($item->subtitle)<div style="color:var(--primary-orange);font-size:.85rem;font-weight:600">{{ $item->subtitle }}</div>@endif
                    @if($item->body)<div style="color:var(--gray-600);font-size:.83rem;margin-top:3px">{{ Str::limit($item->body, 140) }}</div>@endif
                </td>
                <td style="text-align:center">
                    <input type="hidden" form="batch-form" name="items[{{ $item->id }}][is_active]" value="0">
                    <input type="checkbox" form="batch-form" name="items[{{ $item->id }}][is_active]" value="1" {{ $item->is_active ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary-green);cursor:pointer">
                </td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="{{ route('admin.about.items.edit', $item) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>
                    <button type="submit" form="delete-item-{{ $item->id }}" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Delete {{ addslashes($item->title) }}?')"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--gray-600);padding:30px">Nothing here yet. <a href="{{ route('admin.about.items.create', [$page, $group]) }}">Add the first one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endforeach

@if($page->items->isNotEmpty())
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
    <p style="color:var(--gray-600);font-size:.85rem"><i class="fa fa-circle-info me-1"></i> Lower order numbers show first. Untick "Show" to hide an item without deleting it.</p>
    <button type="submit" form="batch-form" class="btn btn-primary"><i class="fa fa-save"></i> Save Order &amp; Visibility</button>
</div>
@endif

@foreach($page->items as $item)
<form id="delete-item-{{ $item->id }}" method="POST" action="{{ route('admin.about.items.destroy', $item) }}" style="display:none">
    @csrf
    @method('DELETE')
</form>
@endforeach
@endsection
