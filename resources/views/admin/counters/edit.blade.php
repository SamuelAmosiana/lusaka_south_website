@extends('layouts.admin')
@section('title', 'Edit Counter')
@section('page-title', 'Edit Counter')

@section('content')
<div class="card" style="max-width: 750px;">
    <div class="card-header">
        <h3><i class="fa fa-pencil me-2"></i>Edit Counter: {{ $counter->label }}</h3>
        <a href="{{ route('admin.counters.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> Back to Counters</a>
    </div>

    @if($errors->any())
    <div class="alert-error">
        <ul style="margin-left: 20px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.counters.update', $counter) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="label">Metric / Label *</label>
                <input type="text" name="label" id="label" class="form-control" placeholder="e.g. Enrolled Students" value="{{ old('label', $counter->label) }}" required>
            </div>
            <div class="form-group">
                <label for="number">Target Number *</label>
                <input type="number" name="number" id="number" class="form-control" placeholder="e.g. 1000" value="{{ old('number', $counter->number) }}" min="0" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="suffix">Suffix (optional)</label>
                <input type="text" name="suffix" id="suffix" class="form-control" placeholder="e.g. + or %" value="{{ old('suffix', $counter->suffix) }}" maxlength="10">
                <small style="color:var(--gray-600)">Appears right after the number (e.g. 1000+)</small>
            </div>
            <div class="form-group">
                <label for="sort_order">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $counter->sort_order) }}" min="0">
                <small style="color:var(--gray-600)">Order of appearance from left to right</small>
            </div>
        </div>

        <div class="form-group">
            <label for="icon">Icon Class *</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <div id="icon-preview" style="width: 44px; height: 44px; border-radius: 8px; background: var(--gray-100); border: 2px solid var(--gray-200); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: var(--primary-green);">
                    <i class="fa {{ old('icon', $counter->icon) }}"></i>
                </div>
                <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $counter->icon) }}" placeholder="fa-user-graduate" required oninput="updateIconPreview(this.value)">
            </div>
            <div style="margin-top: 10px;">
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--gray-600); display: block; margin-bottom: 6px;">Quick Presets:</span>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @php
                    $presets = [
                        'fa-user-graduate' => 'Student',
                        'fa-book-open' => 'Book',
                        'fa-university' => 'University',
                        'fa-award' => 'Award',
                        'fa-users' => 'Users',
                        'fa-certificate' => 'Certificate',
                        'fa-chalkboard-user' => 'Lecturer',
                        'fa-laptop-code' => 'Code/IT',
                        'fa-briefcase' => 'Business',
                        'fa-globe' => 'Globe',
                    ];
                    @endphp
                    @foreach($presets as $cls => $name)
                    <button type="button" class="btn btn-sm btn-outline" style="padding: 4px 8px; font-size: 0.75rem;" onclick="selectPreset('{{ $cls }}')">
                        <i class="fa {{ $cls }} me-1"></i> {{ $name }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $counter->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary-green);">
                <span>Display on Home Page (Active)</span>
            </label>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Counter</button>
            <a href="{{ route('admin.counters.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<script>
function updateIconPreview(val) {
    val = val.trim();
    if (!val.startsWith('fa-')) val = 'fa-' + val;
    document.getElementById('icon-preview').innerHTML = '<i class="fa ' + val + '"></i>';
}
function selectPreset(cls) {
    document.getElementById('icon').value = cls;
    updateIconPreview(cls);
}
</script>
@endsection
