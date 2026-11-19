@extends('layouts.admin')
@php
    $groupConfig = $item->groupConfig();
    $spec = $item->fieldSpec();
    $noun = $item->noun();
    $editing = $item->exists;
    $label = fn($field) => $spec[$field][0] . (!empty($spec[$field][1]) ? ' *' : '');
    $req = fn($field) => !empty($spec[$field][1]) ? 'required' : '';
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
                <label for="title">{{ $label('title') }}</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $item->title) }}" {{ $req('title') }}>
            </div>
            <div class="form-group">
                <label for="sort_order">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $item->sort_order) }}" min="0" placeholder="Leave empty to add at the end">
            </div>
        </div>

        @if(isset($spec['value']) || isset($spec['subtitle']))
        <div class="form-row">
            @isset($spec['value'])
            <div class="form-group">
                <label for="value">{{ $label('value') }}</label>
                <input type="text" name="value" id="value" class="form-control" value="{{ old('value', $item->value) }}" {{ $req('value') }}>
            </div>
            @endisset
            @isset($spec['subtitle'])
            <div class="form-group">
                <label for="subtitle">{{ $label('subtitle') }}</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $item->subtitle) }}" {{ $req('subtitle') }}>
            </div>
            @endisset
        </div>
        @endif

        @isset($spec['url'])
        <div class="form-group">
            <label for="url">{{ $label('url') }}</label>
            <input type="url" name="url" id="url" class="form-control" value="{{ old('url', $item->url) }}" {{ $req('url') }} placeholder="https://">
        </div>
        @endisset

        @if(isset($spec['phone']) || isset($spec['email']))
        <div class="form-row">
            @isset($spec['phone'])
            <div class="form-group">
                <label for="phone">{{ $label('phone') }}</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $item->phone) }}" placeholder="+260 ...">
            </div>
            @endisset
            @isset($spec['email'])
            <div class="form-group">
                <label for="email">{{ $label('email') }}</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $item->email) }}" placeholder="office@lsc.edu.zm">
            </div>
            @endisset
        </div>
        @endif

        @isset($spec['body'])
        <div class="form-group">
            <label for="body">{{ $label('body') }}</label>
            <textarea name="body" id="body" class="form-control" rows="4" {{ $req('body') }}>{{ old('body', $item->body) }}</textarea>
            @if(in_array($item->type(), ['contact', 'info']))<small style="color:var(--gray-600)">Each new line shows on its own line.@if($item->type() === 'info') Phone numbers and email addresses become clickable on the website.@endif</small>@endif
        </div>
        @endisset

        @isset($spec['image'])
        @php $round = $item->type() === 'person'; @endphp
        <div class="form-group">
            <label for="image_file">{{ $label('image') }} <span style="font-weight:400;color:var(--gray-600)">(JPG, PNG or WebP, max 5MB)</span></label>
            <div style="display:flex;gap:15px;align-items:center">
                <div id="photo-preview" style="width:{{ $round ? '80px' : '140px' }};height:80px;border-radius:{{ $round ? '50%' : '10px' }};overflow:hidden;border:3px solid var(--primary-orange);background:var(--gray-200);display:flex;align-items:center;justify-content:center;color:var(--gray-600);flex-shrink:0">
                    @if($item->image_url)<img src="{{ $item->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover">@else<i class="fa {{ $round ? 'fa-user' : 'fa-image' }} fa-2x"></i>@endif
                </div>
                <div style="flex:1">
                    <input type="file" name="image_file" id="image_file" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewPhoto(this)" {{ $req('image') && !$item->image ? 'required' : '' }}>
                    @if($item->image && empty($spec['image'][1]))
                    <label style="display:flex;align-items:center;gap:8px;margin-top:8px;font-weight:400;font-size:.85rem;cursor:pointer">
                        <input type="checkbox" name="remove_image" value="1" style="accent-color:#dc3545"> Remove current picture
                    </label>
                    @elseif($item->image)
                    <small style="color:var(--gray-600);display:block;margin-top:6px">Choose a new file only if you want to replace the current picture.</small>
                    @endif
                </div>
            </div>
        </div>
        @endisset

        @isset($spec['icon'])
        <div class="form-group">
            <label for="icon">{{ $label('icon') }}</label>
            <div style="display:flex;gap:10px;align-items:center">
                <div id="icon-preview" style="width:44px;height:44px;border-radius:8px;background:var(--gray-100);border:2px solid var(--gray-200);display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:var(--primary-green);flex-shrink:0">
                    <i class="fa {{ old('icon', $item->icon ?: 'fa-star') }}"></i>
                </div>
                <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $item->icon ?: 'fa-star') }}" {{ $req('icon') }} oninput="updateIconPreview(this.value)">
            </div>
            <div style="margin-top:10px">
                <span style="font-size:.8rem;font-weight:600;color:var(--gray-600);display:block;margin-bottom:6px">Quick picks (or any icon from fontawesome.com, e.g. fa-landmark):</span>
                <div style="display:flex;flex-wrap:wrap;gap:8px">
                    @foreach(['fa-book-open','fa-bullseye','fa-eye','fa-building','fa-certificate','fa-medal','fa-star','fa-globe','fa-industry','fa-handshake','fa-landmark','fa-flask','fa-money-bill-wave','fa-graduation-cap','fa-users','fa-shield-halved','fa-lightbulb','fa-book','fa-database','fa-play-circle','fa-laptop','fa-location-dot','fa-phone','fa-envelope','fa-clock','fa-user-graduate','fa-briefcase'] as $preset)
                    <button type="button" class="btn btn-sm btn-outline" style="padding:6px 9px" title="{{ $preset }}" onclick="selectPreset('{{ $preset }}')"><i class="fa {{ $preset }}"></i></button>
                    @endforeach
                </div>
            </div>
        </div>
        @endisset

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
    document.getElementById('icon-preview').innerHTML = '<i class="fa ' + val.trim().replace(/[^a-z0-9- ]/gi, '') + '"></i>';
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
