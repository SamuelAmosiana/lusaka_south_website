@extends('layouts.admin')
@php
    $editing = $section->exists;
    // PHP upload limits on this server; one save can't send more than this
    $toMb = fn($v) => (int) $v * (['g' => 1024, 'm' => 1, 'k' => 1 / 1024][strtolower(substr(trim($v), -1))] ?? 1 / 1048576);
    $maxFiles = (int) ini_get('max_file_uploads');
    $maxPostMb = (int) floor($toMb(ini_get('post_max_size')));
@endphp
@section('title', $editing ? 'Edit Gallery Section' : 'Add Gallery Section')
@section('page-title', $editing ? 'Gallery: ' . $section->title : 'Add Gallery Section')

@section('styles')
<style>
.photo-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px}
.photo-card{border:2px solid var(--gray-200);border-radius:10px;overflow:hidden;background:#fff;transition:opacity .2s ease}
.photo-card img{width:100%;height:130px;object-fit:cover;display:block;background:var(--gray-100)}
.photo-card .missing{height:130px;display:flex;align-items:center;justify-content:center;background:var(--gray-100);color:var(--gray-600);font-size:.85rem}
.photo-card .body{padding:10px}
.photo-card .body .form-control{padding:6px 9px;font-size:.85rem}
.photo-card .controls{display:flex;justify-content:space-between;align-items:center;gap:8px;margin-top:8px;font-size:.82rem}
.photo-card .controls label{display:flex;align-items:center;gap:5px;cursor:pointer;margin:0;font-weight:600}
.photo-card.hidden-photo{opacity:.55}
.photo-card.removing{opacity:.35;border-color:#dc3545}
.drop-zone{border:2px dashed var(--primary-green);border-radius:10px;padding:25px;text-align:center;background:#f6fbf6;cursor:pointer}
.drop-zone.dragover{background:#e8f5e9}
</style>
@endsection

@section('content')
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> All Gallery Sections</a>
    <a href="{{ route('gallery') }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View Gallery Page</a>
</div>

@if($errors->any())
<div class="alert-error">
    <ul style="margin-left:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form method="POST" enctype="multipart/form-data" action="{{ $editing ? route('admin.gallery.update', $section) : route('admin.gallery.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="card">
        <div class="card-header"><h3><i class="fa fa-heading me-2"></i>Section Details</h3></div>
        <div class="form-row">
            <div class="form-group">
                <label for="title">Section Heading *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $section->title) }}" required placeholder="e.g. Graduation 2026">
            </div>
            <div class="form-group">
                <label for="subtitle">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $section->subtitle) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="sort_order">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $section->sort_order) }}" min="0" placeholder="Leave empty to add at the end">
            </div>
            <div class="form-group" style="display:flex;align-items:flex-end">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:12px">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $section->is_active) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary-green)">
                    Show this section on the Gallery page
                </label>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3><i class="fa fa-cloud-arrow-up me-2"></i>Add Photos</h3></div>
        <label class="drop-zone" id="drop-zone" for="new_photos">
            <i class="fa fa-images fa-2x" style="color:var(--primary-green)"></i>
            <div style="margin-top:8px;font-weight:600">Click to choose photos, or drag them here</div>
            <div style="color:var(--gray-600);font-size:.85rem;margin-top:4px">You can pick several at once. JPG, PNG or WebP, up to 5MB each, and up to {{ $maxFiles }} photos ({{ $maxPostMb }}MB in total) per save.</div>
        </label>
        <input type="file" name="new_photos[]" id="new_photos" multiple accept="image/jpeg,image/png,image/webp" style="display:none">
        <div class="alert-error" id="upload-warning" style="display:none;margin-top:16px"></div>
        <div class="photo-grid" id="new-previews" style="margin-top:16px"></div>
        <p style="color:var(--gray-600);font-size:.83rem;margin-top:10px"><i class="fa fa-circle-info me-1"></i> Captions are optional; if left empty, the file name is used (e.g. "graduation-day.jpg" becomes "Graduation Day"). New photos are saved when you click Save below.</p>
    </div>

    @if($editing)
    <div class="card">
        <div class="card-header"><h3><i class="fa fa-image me-2"></i>Photos in this Section ({{ $section->photos->count() }})</h3></div>
        @if($section->photos->isEmpty())
            <p style="color:var(--gray-600);text-align:center;padding:20px">No photos yet. Add some above.</p>
        @else
        <div class="photo-grid">
            @foreach($section->photos as $photo)
            <div class="photo-card {{ $photo->is_active ? '' : 'hidden-photo' }}">
                @if($photo->image_url)
                    <img src="{{ $photo->image_url }}" alt="{{ $photo->caption }}" loading="lazy">
                @else
                    <div class="missing"><i class="fa fa-triangle-exclamation me-1"></i> Image file missing</div>
                @endif
                <div class="body">
                    <input type="text" name="photos[{{ $photo->id }}][caption]" value="{{ old("photos.{$photo->id}.caption", $photo->caption) }}" class="form-control" placeholder="Caption">
                    <div class="controls">
                        <input type="number" name="photos[{{ $photo->id }}][sort_order]" value="{{ $photo->sort_order }}" class="form-control" style="width:70px;text-align:center" min="0" title="Order">
                        <label title="Show on website">
                            <input type="hidden" name="photos[{{ $photo->id }}][is_active]" value="0">
                            <input type="checkbox" name="photos[{{ $photo->id }}][is_active]" value="1" {{ $photo->is_active ? 'checked' : '' }} style="accent-color:var(--primary-green)" onchange="this.closest('.photo-card').classList.toggle('hidden-photo', !this.checked)"> Show
                        </label>
                        <label title="Delete this photo when saving" style="color:#dc3545">
                            <input type="checkbox" name="photos[{{ $photo->id }}][remove]" value="1" style="accent-color:#dc3545" onchange="this.closest('.photo-card').classList.toggle('removing', this.checked)"> Remove
                        </label>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <p style="color:var(--gray-600);font-size:.83rem;margin-top:12px"><i class="fa fa-circle-info me-1"></i> Untick <strong>Show</strong> to hide a photo, or tick <strong>Remove</strong> to delete it. Lower order numbers show first.</p>
        @endif
    </div>
    @endif

    <div style="display:flex;gap:10px">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ $editing ? 'Save Section & Photos' : 'Create Section' }}</button>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

<script>
(() => {
    const input = document.getElementById('new_photos');
    const zone = document.getElementById('drop-zone');
    const previews = document.getElementById('new-previews');
    const headline = name => name.replace(/\.[^.]+$/, '').replace(/[-_]+/g, ' ').replace(/\s+/g, ' ').trim().replace(/\b\w/g, c => c.toUpperCase());

    const maxFiles = {{ $maxFiles }}, maxTotal = {{ $maxPostMb }} * 1024 * 1024;
    const warning = document.getElementById('upload-warning');

    function render() {
        previews.innerHTML = '';
        const files = [...input.files];
        const total = files.reduce((sum, f) => sum + f.size, 0);
        const problems = [];
        if (files.length > maxFiles) problems.push('You selected ' + files.length + ' photos; please add at most ' + maxFiles + ' per save.');
        if (total > maxTotal) problems.push('These photos total ' + (total / 1048576).toFixed(1) + ' MB; please keep each save under ' + (maxTotal / 1048576) + ' MB.');
        warning.style.display = problems.length ? 'block' : 'none';
        warning.textContent = problems.join(' ');
        [...input.files].forEach((file, i) => {
            const card = document.createElement('div');
            card.className = 'photo-card';
            const img = document.createElement('img');
            img.alt = '';
            img.src = URL.createObjectURL(file);
            const body = document.createElement('div');
            body.className = 'body';
            const caption = document.createElement('input');
            caption.type = 'text';
            caption.name = 'new_captions[' + i + ']';
            caption.className = 'form-control';
            caption.placeholder = headline(file.name);
            const size = document.createElement('div');
            size.style.cssText = 'font-size:.78rem;color:' + (file.size > 5 * 1024 * 1024 ? '#dc3545' : 'var(--gray-600)') + ';margin-top:6px';
            size.textContent = file.name + ' · ' + (file.size / 1024 / 1024).toFixed(1) + ' MB' + (file.size > 5 * 1024 * 1024 ? ' (too large, max 5MB)' : '');
            body.append(caption, size);
            card.append(img, body);
            previews.append(card);
        });
    }

    input.addEventListener('change', render);
    ['dragenter', 'dragover'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.add('dragover'); }));
    ['dragleave', 'drop'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.remove('dragover'); }));
    zone.addEventListener('drop', e => {
        const files = [...e.dataTransfer.files].filter(f => f.type.startsWith('image/'));
        if (!files.length) return;
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
        render();
    });
})();
</script>
@endsection
