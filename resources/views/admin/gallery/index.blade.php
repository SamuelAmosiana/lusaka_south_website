@extends('layouts.admin')
@section('title', 'Gallery')
@section('page-title', 'Gallery')

@section('styles')
<style>
.thumbs{display:flex;gap:4px}
.thumbs img{width:44px;height:44px;object-fit:cover;border-radius:6px}
tr.inactive td{opacity:.55}
.table-wrap{overflow-x:auto}
</style>
@endsection

@section('content')
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('gallery') }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View Gallery Page</a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-images me-2"></i>Gallery Sections</h3>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-orange btn-sm"><i class="fa fa-plus"></i> Add Section</a>
    </div>
    <p style="color:var(--gray-600);margin-bottom:20px">Each section is a heading on the Gallery page with its own photos. Click <i class="fa fa-pencil"></i> to add, caption, reorder or remove photos.</p>
    <form method="POST" action="{{ route('admin.gallery.batch') }}" id="batch-form">@csrf</form>
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:80px">Order</th>
                <th>Section</th>
                <th style="width:200px">Photos</th>
                <th style="width:80px;text-align:center">Show</th>
                <th style="width:110px;text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sections as $section)
            <tr class="{{ $section->is_active ? '' : 'inactive' }}">
                <td><input type="number" form="batch-form" name="sections[{{ $section->id }}][sort_order]" value="{{ $section->sort_order }}" class="form-control" style="padding:6px 8px;text-align:center" min="0"></td>
                <td>
                    <strong>{{ $section->title }}</strong>
                    @if($section->subtitle)<div style="color:var(--gray-600);font-size:.83rem;margin-top:3px">{{ $section->subtitle }}</div>@endif
                </td>
                <td>
                    <div class="thumbs">
                        @foreach($section->photos as $photo)
                            @if($photo->image_url)<img src="{{ $photo->image_url }}" alt="{{ $photo->caption }}">@endif
                        @endforeach
                    </div>
                    <div style="font-size:.8rem;color:var(--gray-600);margin-top:4px">
                        {{ $section->active_photos_count }} shown
                        @if($section->photos_count > $section->active_photos_count)
                            &middot; {{ $section->photos_count - $section->active_photos_count }} hidden
                        @endif
                    </div>
                </td>
                <td style="text-align:center">
                    <input type="hidden" form="batch-form" name="sections[{{ $section->id }}][is_active]" value="0">
                    <input type="checkbox" form="batch-form" name="sections[{{ $section->id }}][is_active]" value="1" {{ $section->is_active ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary-green);cursor:pointer">
                </td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="{{ route('admin.gallery.edit', $section) }}" class="btn btn-primary btn-sm" title="Edit section and photos"><i class="fa fa-pencil"></i></a>
                    <form method="POST" action="{{ route('admin.gallery.destroy', $section) }}" style="display:inline" onsubmit="return confirm(@js('Delete the section ' . $section->title . ' and all its photos?'))">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--gray-600);padding:30px">No gallery sections yet. <a href="{{ route('admin.gallery.create') }}">Add the first one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($sections->isNotEmpty())
    <div style="margin-top:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <p style="color:var(--gray-600);font-size:.85rem"><i class="fa fa-circle-info me-1"></i> Lower order numbers show first.</p>
        <button type="submit" form="batch-form" class="btn btn-primary"><i class="fa fa-save"></i> Save Order &amp; Visibility</button>
    </div>
    @endif
</div>
@endsection
