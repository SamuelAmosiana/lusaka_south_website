@extends('layouts.admin')
@php
    $groupConfig = $item->groupConfig();
    $type = $groupConfig['type'];
    $isPerson = $type === 'person';
    $describe = ! $isPerson || ($groupConfig['describe'] ?? false);
    $noun = ['person' => 'Person', 'link' => 'Resource', 'contact' => 'Contact Card'][$type] ?? 'Card';
    $editing = $item->exists;
@endphp
@section('title', ($editing ? 'Edit ' : 'Add ') . $noun)
@section('page-title', $page->config()['label'] . ': ' . ($editing ? 'Edit ' : 'Add ') . $noun)

@section('content')
<div class="card" style="max-width:780px">
    <div class="card-header">
        <h3><i class="fa {{ $editing ? 'fa-pencil' : 'fa-plus' }} me-2"></i>{{ $editing ? 'Edit' : 'Add' }} {{ $noun }} &middot; {{ $groupConfig['label'] }}</h3>
        <a href="{{ route('admin.about.edit', $page) }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    @if($errors->any())
    <div class="alert-error">
        <ul style="margin-left:20px">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $editing ? route('admin.about.items.update', $item) : route('admin.about.items.store', [$page, $item->group]) }}">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="form-row">
            <div class="form-group">
                <label for="title">{{ ['person' => 'Name', 'link' => 'Resource Name', 'contact' => 'Department / Office'][$type] ?? 'Title' }} *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $item->title) }}" required>
            </div>
            <div class="form-group">
                <label for="sort_order">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $item->sort_order) }}" min="0" placeholder="Leave empty to add at the end">
            </div>
        </div>

        @if($isPerson)
        <div class="form-group">
            <label for="subtitle">Role / Position *</label>
            <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $item->subtitle) }}" required placeholder="e.g. Registrar or Head – School of Law">
        </div>
        @endif

        @if($type === 'link')
        <div class="form-row">
            <div class="form-group">
                <label for="url">Website Link *</label>
                <input type="url" name="url" id="url" class="form-control" value="{{ old('url', $item->url) }}" required placeholder="https://">
            </div>
            <div class="form-group">
                <label for="subtitle">Button Text</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $item->subtitle) }}" placeholder="Visit Website">
            </div>
        </div>
        @endif

        @if($type === 'contact')
        <div class="form-row">
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $item->phone) }}" placeholder="+260 ...">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $item->email) }}" placeholder="office@lsc.edu.zm">
            </div>
        </div>
        @endif

        @if($describe)
        @php $bodyRequired = in_array($type, ['card', 'link']); @endphp
        <div class="form-group">
            <label for="body">{{ $isPerson ? 'Short Description' : 'Text' }}{{ $bodyRequired ? ' *' : '' }}</label>
            <textarea name="body" id="body" class="form-control" rows="4" {{ $bodyRequired ? 'required' : '' }}>{{ old('body', $item->body) }}</textarea>
            @if($type === 'contact')<small style="color:var(--gray-600)">Each new line shows on its own line, e.g. an address followed by opening hours.</small>@endif
        </div>
        @endif

        @if($isPerson)
        <div class="form-group">
            <label for="image_file">Photo (JPG, PNG or WebP, max 5MB)</label>
            <div style="display:flex;gap:15px;align-items:center">
                <div id="photo-preview" style="width:80px;height:80px;border-radius:50%;overflow:hidden;border:3px solid var(--primary-orange);background:var(--gray-200);display:flex;align-items:center;justify-content:center;color:var(--gray-600);flex-shrink:0">
                    @if($item->image_url)<img src="{{ $item->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover">@else<i class="fa fa-user fa-2x"></i>@endif
                </div>
                <div style="flex:1">
                    <input type="file" name="image_file" id="image_file" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewPhoto(this)">
                    @if($item->image)
                    <label style="display:flex;align-items:center;gap:8px;margin-top:8px;font-weight:400;font-size:.85rem;cursor:pointer">
                        <input type="checkbox" name="remove_image" value="1" style="accent-color:#dc3545"> Remove current photo
                    </label>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="form-group">
            <label for="icon">Icon *</label>
            <div style="display:flex;gap:10px;align-items:center">
                <div id="icon-preview" style="width:44px;height:44px;border-radius:8px;background:var(--gray-100);border:2px solid var(--gray-200);display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:var(--primary-green);flex-shrink:0">
                    <i class="fa {{ old('icon', $item->icon ?: 'fa-star') }}"></i>
                </div>
                <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $item->icon ?: 'fa-star') }}" required oninput="updateIconPreview(this.value)">
            </div>
            <div style="margin-top:10px">
                <span style="font-size:.8rem;font-weight:600;color:var(--gray-600);display:block;margin-bottom:6px">Quick picks (or any icon from fontawesome.com, e.g. fa-landmark):</span>
                <div style="display:flex;flex-wrap:wrap;gap:8px">
                    @foreach(['fa-book-open','fa-bullseye','fa-eye','fa-building','fa-certificate','fa-medal','fa-star','fa-globe','fa-industry','fa-handshake','fa-landmark','fa-flask','fa-money-bill-wave','fa-graduation-cap','fa-users','fa-shield-halved','fa-book','fa-database','fa-play-circle','fa-laptop','fa-location-dot','fa-briefcase'] as $preset)
                    <button type="button" class="btn btn-sm btn-outline" style="padding:6px 9px" title="{{ $preset }}" onclick="selectPreset('{{ $preset }}')"><i class="fa {{ $preset }}"></i></button>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="form-group" style="margin-top:20px">
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary-green)">
                <span>Show on the website</span>
            </label>
        </div>

        <div style="margin-top:25px;display:flex;gap:10px">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ $editing ? 'Save Changes' : 'Add ' . $noun }}</button>
            <a href="{{ route('admin.about.edit', $page) }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<script>
function updateIconPreview(val) {
    val = val.trim();
    document.getElementById('icon-preview').innerHTML = '<i class="fa ' + val.replace(/[^a-z0-9- ]/gi, '') + '"></i>';
}
function selectPreset(cls) {
    document.getElementById('icon').value = cls;
    updateIconPreview(cls);
}
function previewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photo-preview').innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover">';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endsection
