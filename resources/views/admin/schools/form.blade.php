@extends('layouts.admin')
@php $editing = $school->exists; @endphp
@section('title', $editing ? 'Edit School' : 'Add School')
@section('page-title', $editing ? 'Edit School: ' . $school->name : 'Add School')

@section('styles')
<style>
.prog-table td{padding:8px 10px;vertical-align:middle}
.prog-table .form-control{padding:7px 10px;font-size:.9rem}
.prog-table tr.removing td{opacity:.45;text-decoration:line-through}
.prog-table tr.hidden-prog td:not(:last-child){opacity:.6}
.table-wrap{overflow-x:auto}
</style>
@endsection

@section('content')
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('admin.schools.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> All Schools</a>
    <a href="{{ route('schools') }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View Schools Page</a>
</div>

@if($errors->any())
<div class="alert-error">
    <ul style="margin-left:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form method="POST" action="{{ $editing ? route('admin.schools.update', $school) : route('admin.schools.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="card">
        <div class="card-header">
            <h3><i class="fa fa-school me-2"></i>School Details</h3>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="name">School Name *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $school->name) }}" required placeholder="e.g. School of Health Sciences">
            </div>
            <div class="form-group">
                <label for="apply_link">Apply Button Goes To *</label>
                <select name="apply_link" id="apply_link" class="form-control" required>
                    @foreach(\App\Models\School::applyOptions() as $key => $label)
                    <option value="{{ $key }}" @selected(old('apply_link', $school->apply_link) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="intro">Short Introduction</label>
            <textarea name="intro" id="intro" class="form-control" rows="2">{{ old('intro', $school->intro) }}</textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="icon">Icon *</label>
                <div style="display:flex;gap:10px;align-items:center">
                    <div id="icon-preview" style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0">
                        <i class="fa {{ old('icon', $school->icon) }}"></i>
                    </div>
                    <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $school->icon) }}" required oninput="updateIconPreview(this.value)">
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px">
                    @foreach(['fa-briefcase','fa-scale-balanced','fa-gears','fa-screwdriver-wrench','fa-laptop-code','fa-stethoscope','fa-chalkboard-user','fa-seedling','fa-calculator','fa-graduation-cap'] as $preset)
                    <button type="button" class="btn btn-sm btn-outline" style="padding:5px 8px" title="{{ $preset }}" onclick="selectPreset('{{ $preset }}')"><i class="fa {{ $preset }}"></i></button>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label for="sort_order">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $school->sort_order) }}" min="0" placeholder="Leave empty to add at the end">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-top:18px;font-weight:600">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $school->is_active) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary-green)">
                    Show this school on the website
                </label>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fa fa-list-ul me-2"></i>Programmes</h3>
            <button type="button" class="btn btn-orange btn-sm" onclick="addProgramme()"><i class="fa fa-plus"></i> Add Programme</button>
        </div>
        <div class="table-wrap">
        <table class="prog-table">
            <thead>
                <tr>
                    <th style="width:80px">Order</th>
                    <th>Programme Name</th>
                    <th style="width:160px">Level</th>
                    <th style="width:70px;text-align:center">Show</th>
                    <th style="width:80px;text-align:center">Remove</th>
                </tr>
            </thead>
            <tbody id="programme-rows">
                @php
                    $rows = old('programmes', $school->programmes->map(fn($p) => [
                        'id' => $p->id, 'name' => $p->name, 'level' => $p->level, 'sort_order' => $p->sort_order, 'is_active' => $p->is_active,
                    ])->all());
                @endphp
                @foreach($rows as $i => $row)
                <tr class="{{ empty($row['is_active']) ? 'hidden-prog' : '' }}">
                    <td>
                        @if(!empty($row['id']))<input type="hidden" name="programmes[{{ $i }}][id]" value="{{ $row['id'] }}">@endif
                        <input type="number" name="programmes[{{ $i }}][sort_order]" value="{{ $row['sort_order'] ?? '' }}" class="form-control" style="text-align:center" min="0">
                    </td>
                    <td><input type="text" name="programmes[{{ $i }}][name]" value="{{ $row['name'] ?? '' }}" class="form-control" placeholder="e.g. Diploma in Public Health"></td>
                    <td>
                        <select name="programmes[{{ $i }}][level]" class="form-control">
                            @foreach(\App\Models\Programme::LEVELS as $level)
                            <option value="{{ $level }}" @selected(($row['level'] ?? '') === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="text-align:center">
                        <input type="hidden" name="programmes[{{ $i }}][is_active]" value="0">
                        <input type="checkbox" name="programmes[{{ $i }}][is_active]" value="1" {{ !empty($row['is_active']) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary-green)" onchange="this.closest('tr').classList.toggle('hidden-prog', !this.checked)">
                    </td>
                    <td style="text-align:center">
                        <input type="checkbox" name="programmes[{{ $i }}][remove]" value="1" {{ !empty($row['remove']) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#dc3545" title="Remove this programme" onchange="this.closest('tr').classList.toggle('removing', this.checked)">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <p id="no-programmes" style="text-align:center;color:var(--gray-600);padding:20px;{{ count($rows) ? 'display:none' : '' }}">No programmes yet. Click <strong>Add Programme</strong>.</p>
        <p style="color:var(--gray-600);font-size:.85rem;margin-top:15px"><i class="fa fa-circle-info me-1"></i> Untick <strong>Show</strong> to hide a programme without deleting it. Tick <strong>Remove</strong> to delete it when you save. Empty new rows are ignored. Schools with {{ \App\Models\School::WIDE_FROM }} or more programmes are shown full width in two columns.</p>
    </div>

    <div style="display:flex;gap:10px">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ $editing ? 'Save School & Programmes' : 'Add School' }}</button>
        <a href="{{ route('admin.schools.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

<template id="programme-template">
    <tr>
        <td><input type="number" name="programmes[__i__][sort_order]" class="form-control" style="text-align:center" min="0"></td>
        <td><input type="text" name="programmes[__i__][name]" class="form-control" placeholder="e.g. Diploma in Public Health"></td>
        <td>
            <select name="programmes[__i__][level]" class="form-control">
                @foreach(\App\Models\Programme::LEVELS as $level)<option value="{{ $level }}">{{ $level }}</option>@endforeach
            </select>
        </td>
        <td style="text-align:center">
            <input type="hidden" name="programmes[__i__][is_active]" value="0">
            <input type="checkbox" name="programmes[__i__][is_active]" value="1" checked style="width:18px;height:18px;accent-color:var(--primary-green)" onchange="this.closest('tr').classList.toggle('hidden-prog', !this.checked)">
        </td>
        <td style="text-align:center"><button type="button" class="btn btn-danger btn-sm" title="Discard this new row" onclick="this.closest('tr').remove()"><i class="fa fa-xmark"></i></button></td>
    </tr>
</template>

<script>
let nextRow = {{ count($rows) ? max(array_keys($rows)) + 1 : 0 }};
function addProgramme(focus = true) {
    const html = document.getElementById('programme-template').innerHTML.replaceAll('__i__', nextRow++);
    const body = document.getElementById('programme-rows');
    body.insertAdjacentHTML('beforeend', html);
    document.getElementById('no-programmes').style.display = 'none';
    if (focus) body.lastElementChild.querySelector('input[type=text]').focus();
}
function updateIconPreview(val) {
    document.getElementById('icon-preview').innerHTML = '<i class="fa ' + val.trim().replace(/[^a-z0-9- ]/gi, '') + '"></i>';
}
function selectPreset(cls) {
    document.getElementById('icon').value = cls;
    updateIconPreview(cls);
}
@if(!$editing && !old('programmes'))
addProgramme(false);
@endif
</script>
@endsection
