@extends('layouts.admin')
@php
    $config = $page->config();
    $area = $config['area'];
    $isHome = $area === 'home';
    $fields = $page->fields();
@endphp
@section('title', 'Edit ' . $config['label'])
@section('page-title', (['about' => 'About Pages: ', 'home' => 'Home Page: '][$area] ?? '') . $config['label'])

@section('styles')
<style>
.item-thumb{width:46px;height:46px;border-radius:50%;object-fit:cover;border:2px solid var(--primary-orange)}
.item-thumb.wide{width:72px;border-radius:8px}
.item-icon{width:40px;height:40px;border-radius:8px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:var(--primary-green)}
.item-placeholder{width:46px;height:46px;border-radius:50%;background:var(--gray-200);display:flex;align-items:center;justify-content:center;color:var(--gray-600)}
.item-value{display:inline-block;background:var(--primary-orange);color:#fff;font-weight:800;padding:6px 10px;border-radius:8px;font-size:.95rem}
tr.inactive td{opacity:.55}
.table-wrap{overflow-x:auto}
</style>
@endsection

@section('content')
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    @if($area === 'about')
    <a href="{{ route('admin.about.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> All About Pages</a>
    @elseif($isHome)
    <a href="{{ route('admin.home.index') }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> All Home Page Sections</a>
    @endif
    <a href="{{ route($config['route']) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View {{ $isHome ? 'Home Page' : 'Page on Website' }}</a>
</div>

@if(!empty($config['description']))
<p style="color:var(--gray-600);margin:-5px 0 20px"><i class="fa fa-circle-info me-1"></i> {{ $config['description'] }}</p>
@endif

{{-- Page / section text --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-heading me-2"></i>{{ $isHome ? 'Section Headings & Text' : 'Page Headings & Text' }}</h3>
    </div>

    @if($errors->any())
    <div class="alert-error">
        <ul style="margin-left:20px">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.about.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-row">
        @foreach($fields as $field => $spec)
            @if(!empty($spec['image']))
            </div>
            @php
                $isLogo = $field === 'logo';
                $current = $page->fileUrl($field);
                $shown = $current ?? (!empty($spec['default']) ? asset($spec['default']) : null);
            @endphp
            <div class="form-group" style="{{ $isLogo ? 'border-top:2px solid var(--gray-200);padding-top:18px;margin-top:6px' : '' }}">
                <label for="{{ $field }}_file">{{ $spec['label'] }} <span style="font-weight:400;color:var(--gray-600)">(JPG, PNG or WebP, max 5MB)</span></label>
                <div style="display:flex;gap:15px;align-items:center;flex-wrap:wrap">
                    @if($isLogo)
                    <div style="width:84px;height:84px;border-radius:50%;background:#fff;border:2px solid var(--primary-orange);padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.15);flex-shrink:0">
                        <img id="{{ $field }}-preview" src="{{ $shown }}" alt="Current logo" style="width:100%;height:100%;object-fit:contain">
                    </div>
                    @elseif($shown)
                    <img id="{{ $field }}-preview" src="{{ $shown }}" alt="" style="width:200px;height:100px;object-fit:cover;border-radius:10px;border:2px solid var(--gray-200)">
                    @endif
                    <div style="flex:1;min-width:220px">
                        <input type="file" name="{{ $field }}_file" id="{{ $field }}_file" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewPicture(this, @js($field . '-preview'))">
                        <small style="color:var(--gray-600);display:block;margin-top:4px">{{ $spec['help'] ?? '' }} Choose a new file only if you want to replace the current one.</small>
                        @if($isLogo)
                        <small style="color:var(--gray-600);display:block;margin-top:4px">{{ $current ? 'Using your uploaded logo.' : 'Using the default logo (' . basename($spec['default']) . ').' }}</small>
                        @endif
                        @if($current && str_starts_with($page->$field, 'images/about/'))
                        <label style="display:flex;align-items:center;gap:8px;margin-top:8px;font-weight:400;font-size:.85rem;cursor:pointer">
                            <input type="checkbox" name="reset_{{ $field }}" value="1" style="accent-color:#dc3545"> {{ $isLogo ? 'Go back to the default logo' : 'Go back to the original picture' }}
                        </label>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-row">
            @elseif(!empty($spec['textarea']))
            </div>
            <div class="form-group">
                <label for="{{ $field }}">{{ $spec['label'] }}{{ !empty($spec['required']) ? ' *' : '' }}</label>
                <textarea name="{{ $field }}" id="{{ $field }}" class="form-control" rows="3" {{ !empty($spec['required']) ? 'required' : '' }}>{{ old($field, $page->$field) }}</textarea>
            </div>
            <div class="form-row">
            @else
            <div class="form-group">
                <label for="{{ $field }}">{{ $spec['label'] }}{{ !empty($spec['required']) ? ' *' : '' }}</label>
                <input type="text" name="{{ $field }}" id="{{ $field }}" class="form-control" value="{{ old($field, $page->$field) }}" {{ !empty($spec['required']) ? 'required' : '' }}>
            </div>
            @endif
        @endforeach
        </div>
        @if($config['extra'])
        <h4 style="color:var(--primary-green);margin:10px 0 15px;font-size:1rem"><i class="fa fa-square-plus me-1"></i> {{ $config['extra']['label'] }}</h4>
        <div class="form-group">
            <label for="extra_title">{{ $config['extra']['title'] }}</label>
            <input type="text" name="extra_title" id="extra_title" class="form-control" value="{{ old('extra_title', $page->extra_title) }}">
        </div>
        @if($config['extra']['text'])
        <div class="form-group">
            <label for="extra_text">{{ $config['extra']['text'] }}</label>
            <textarea name="extra_text" id="extra_text" class="form-control" rows="3">{{ old('extra_text', $page->extra_text) }}</textarea>
        </div>
        @endif
        @endif
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save {{ $isHome ? 'Section' : 'Page' }} Text</button>
    </form>
</div>

{{-- Items, one table per group --}}
<form method="POST" action="{{ route('admin.about.batch', $page) }}" id="batch-form">
    @csrf
</form>

@foreach($config['groups'] as $group => $groupConfig)
@php
    $items = $page->items->where('group', $group);
    $type = $groupConfig['type'];
    $spec = \App\Models\AboutItem::TYPES[$type];
    $noun = \App\Models\AboutItem::NOUNS[$type] ?? 'Item';
    $hasImage = isset($spec['image']);
@endphp
<div class="card">
    <div class="card-header">
        <h3><i class="fa {{ $hasImage ? 'fa-image' : 'fa-table-cells-large' }} me-2"></i>{{ $groupConfig['label'] }}</h3>
        <a href="{{ route('admin.about.items.create', [$page, $group]) }}" class="btn btn-orange btn-sm"><i class="fa fa-plus"></i> Add {{ $noun }}</a>
    </div>
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:80px">Order</th>
                @if($hasImage || isset($spec['icon']) || isset($spec['value']))<th style="width:90px">{{ $hasImage ? 'Picture' : (isset($spec['value']) ? 'Value' : 'Icon') }}</th>@endif
                <th>Details</th>
                <th style="width:80px;text-align:center">Show</th>
                <th style="width:110px;text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr class="{{ $item->is_active ? '' : 'inactive' }}">
                <td><input type="number" form="batch-form" name="items[{{ $item->id }}][sort_order]" value="{{ $item->sort_order }}" class="form-control" style="padding:6px 8px;text-align:center" min="0"></td>
                @if($hasImage || isset($spec['icon']) || isset($spec['value']))
                <td>
                    @if($hasImage)
                        @if($item->image_url)<img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="item-thumb {{ $type === 'person' ? '' : 'wide' }}">@else<div class="item-placeholder"><i class="fa {{ $type === 'person' ? 'fa-user' : 'fa-image' }}"></i></div>@endif
                    @elseif(isset($spec['value']))
                        <span class="item-value">{{ $item->value }}</span>
                    @else
                        <div class="item-icon"><i class="fa {{ $item->icon }}"></i></div>
                    @endif
                </td>
                @endif
                <td>
                    <strong>{{ $item->title ?: '(no caption)' }}</strong>
                    @if($item->subtitle)<div style="color:var(--primary-orange);font-size:.85rem;font-weight:600">{{ $type === 'link' ? 'Button: ' : '' }}{{ $item->subtitle }}</div>@endif
                    @if($item->body)<div style="color:var(--gray-600);font-size:.83rem;margin-top:3px;white-space:pre-line">{{ Str::limit($item->body, 160) }}</div>@endif
                    @if($item->url)<div style="font-size:.82rem;margin-top:3px"><i class="fa fa-link me-1"></i><a href="{{ $item->url }}" target="_blank" rel="noopener">{{ $item->url }}</a></div>@endif
                    @if($item->phone || $item->email)<div style="font-size:.82rem;margin-top:3px">@if($item->phone)<i class="fa fa-phone me-1"></i>{{ $item->phone }}&nbsp;&nbsp;@endif @if($item->email)<i class="fa fa-envelope me-1"></i>{{ $item->email }}@endif</div>@endif
                </td>
                <td style="text-align:center">
                    <input type="hidden" form="batch-form" name="items[{{ $item->id }}][is_active]" value="0">
                    <input type="checkbox" form="batch-form" name="items[{{ $item->id }}][is_active]" value="1" {{ $item->is_active ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary-green);cursor:pointer">
                </td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="{{ route('admin.about.items.edit', $item) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>
                    <button type="submit" form="delete-item-{{ $item->id }}" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm(@js('Delete ' . ($item->title ?: 'this ' . strtolower($noun)) . '?'))"><i class="fa fa-trash"></i></button>
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

<script>
// Show a chosen picture straight away, before saving
function previewPicture(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview || !input.files || !input.files[0]) return;
    preview.src = URL.createObjectURL(input.files[0]);
}
</script>
@endsection
