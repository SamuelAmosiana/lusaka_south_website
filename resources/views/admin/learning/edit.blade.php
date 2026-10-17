@extends('layouts.admin')
@section('title', 'Edit Learning Mode')
@section('page-title', 'Edit Learning Mode: ' . $feature->title)

@section('content')
<div class="card" style="max-width: 750px;">
    <div class="card-header">
        <h3><i class="fa fa-pencil me-2"></i>Edit Learning Feature</h3>
        <a href="{{ route('admin.learning.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> Back to Learning Modes</a>
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

    <form method="POST" action="{{ route('admin.learning.features.update', $feature) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $feature->title) }}" required>
            </div>
            <div class="form-group">
                <label for="sort_order">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $feature->sort_order) }}" min="0">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $feature->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="icon">Icon Class *</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <div id="icon-preview" style="width: 44px; height: 44px; border-radius: 8px; background: var(--gray-100); border: 2px solid var(--gray-200); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: var(--primary-green);">
                    <i class="fa {{ old('icon', $feature->icon) }}"></i>
                </div>
                <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $feature->icon) }}" required oninput="updateIconPreview(this.value)">
            </div>
            <div style="margin-top: 10px;">
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--gray-600); display: block; margin-bottom: 6px;">Quick Presets:</span>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @php
                    $presets = [
                        'fa-laptop-code' => 'E-Learning',
                        'fa-chalkboard-user' => 'Lecturers',
                        'fa-comments' => 'Classroom',
                        'fa-building-columns' => 'On-Campus',
                        'fa-book-open' => 'Books / Library',
                        'fa-video' => 'Video Lectures',
                        'fa-desktop' => 'Desktop VLE',
                        'fa-users' => 'Community',
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
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $feature->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary-green);">
                <span>Display on Home Page (Active)</span>
            </label>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Feature</button>
            <a href="{{ route('admin.learning.index') }}" class="btn btn-outline">Cancel</a>
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
