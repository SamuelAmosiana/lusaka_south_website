@extends('layouts.admin')
@section('title', 'Schools & Programmes')
@section('page-title', 'Schools & Programmes')

@section('styles')
<style>
.school-icon-sm{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;display:flex;align-items:center;justify-content:center}
tr.inactive td{opacity:.55}
.table-wrap{overflow-x:auto}
</style>
@endsection

@section('content')
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('schools') }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View Schools Page</a>
</div>

{{-- Schools list --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-school me-2"></i>Schools</h3>
        <a href="{{ route('admin.schools.create') }}" class="btn btn-orange btn-sm"><i class="fa fa-plus"></i> Add School</a>
    </div>
    <form method="POST" action="{{ route('admin.schools.batch') }}" id="batch-form">@csrf</form>
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:80px">Order</th>
                <th style="width:60px">Icon</th>
                <th>School</th>
                <th style="width:150px">Programmes</th>
                <th style="width:80px;text-align:center">Show</th>
                <th style="width:110px;text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schools as $school)
            <tr class="{{ $school->is_active ? '' : 'inactive' }}">
                <td><input type="number" form="batch-form" name="schools[{{ $school->id }}][sort_order]" value="{{ $school->sort_order }}" class="form-control" style="padding:6px 8px;text-align:center" min="0"></td>
                <td><div class="school-icon-sm"><i class="fa {{ $school->icon }}"></i></div></td>
                <td>
                    <strong>{{ $school->name }}</strong>
                    <div style="color:var(--gray-600);font-size:.82rem;margin-top:3px">Apply button: {{ \App\Models\School::applyOptions()[$school->apply_link] ?? $school->apply_link }}</div>
                </td>
                <td>
                    {{ $school->active_programmes_count }} shown
                    @if($school->programmes_count > $school->active_programmes_count)
                    <div style="color:var(--gray-600);font-size:.8rem">{{ $school->programmes_count - $school->active_programmes_count }} hidden</div>
                    @endif
                </td>
                <td style="text-align:center">
                    <input type="hidden" form="batch-form" name="schools[{{ $school->id }}][is_active]" value="0">
                    <input type="checkbox" form="batch-form" name="schools[{{ $school->id }}][is_active]" value="1" {{ $school->is_active ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary-green);cursor:pointer">
                </td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-primary btn-sm" title="Edit school and programmes"><i class="fa fa-pencil"></i></a>
                    <form method="POST" action="{{ route('admin.schools.destroy', $school) }}" style="display:inline" onsubmit="return confirm(@js('Delete ' . $school->name . ' and all its programmes?'))">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:var(--gray-600);padding:30px">No schools yet. <a href="{{ route('admin.schools.create') }}">Add the first one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($schools->isNotEmpty())
    <div style="margin-top:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <p style="color:var(--gray-600);font-size:.85rem"><i class="fa fa-circle-info me-1"></i> Lower order numbers show first. Click <i class="fa fa-pencil"></i> to edit a school's programmes.</p>
        <button type="submit" form="batch-form" class="btn btn-primary"><i class="fa fa-save"></i> Save Order &amp; Visibility</button>
    </div>
    @endif
</div>

{{-- Page text and catalogue --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-heading me-2"></i>Page Headings &amp; Programmes Catalogue</h3>
    </div>

    @if($errors->any())
    <div class="alert-error">
        <ul style="margin-left:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.schools.settings') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label for="header_title">Banner Title *</label>
                <input type="text" name="header_title" id="header_title" class="form-control" value="{{ old('header_title', $settings->header_title) }}" required>
            </div>
            <div class="form-group">
                <label for="header_subtitle">Banner Subtitle</label>
                <input type="text" name="header_subtitle" id="header_subtitle" class="form-control" value="{{ old('header_subtitle', $settings->header_subtitle) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="section_title">Section Heading *</label>
                <input type="text" name="section_title" id="section_title" class="form-control" value="{{ old('section_title', $settings->section_title) }}" required>
            </div>
            <div class="form-group">
                <label for="section_subtitle">Section Subtitle</label>
                <input type="text" name="section_subtitle" id="section_subtitle" class="form-control" value="{{ old('section_subtitle', $settings->section_subtitle) }}">
            </div>
        </div>
        <div class="form-group">
            <label for="catalog_file">Programmes Catalogue PDF (max 20MB)</label>
            <input type="file" name="catalog_file" id="catalog_file" class="form-control" accept="application/pdf">
            <small style="color:var(--gray-600)">
                @if($settings->catalog_url)
                    Current file: <a href="{{ $settings->catalog_url }}" target="_blank">{{ basename($settings->catalog_pdf) }}</a>. Uploading a new PDF replaces it on the Schools page.
                @else
                    No catalogue uploaded, so the "Programmes PDF" buttons are hidden.
                @endif
            </small>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Page Settings</button>
    </form>
</div>
@endsection
