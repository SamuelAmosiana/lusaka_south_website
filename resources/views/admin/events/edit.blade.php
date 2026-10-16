@extends('layouts.admin')
@section('title','Edit Event')
@section('page-title','Edit News/Event')
@section('content')
<div class="card">
    <form method="POST" action="{{ route('admin.events.update',$event) }}">
        @csrf @method('PUT')
        @if($errors->any())<div class="alert-error"><i class="fa fa-triangle-exclamation me-2"></i>Please fix the errors below.</div>@endif
        <div class="form-row">
            <div class="form-group"><label>Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title',$event->title) }}" required>
                @error('title')<small style="color:#dc3545">{{ $message }}</small>@enderror
            </div>
            <div class="form-group"><label>Date *</label>
                <input type="date" name="date" class="form-control" value="{{ old('date',$event->date->format('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Category *</label>
                <select name="category" class="form-control" required>
                    @foreach(['Latest News','Upcoming Event','Past Event','Job Vacancy'] as $cat)
                    <option value="{{ $cat }}" {{ old('category',$event->category)===$cat?'selected':'' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"><label>Author</label>
                <input type="text" name="author" class="form-control" value="{{ old('author',$event->author) }}">
            </div>
        </div>
        <div class="form-group"><label>Short Description *</label>
            <textarea name="short_description" class="form-control" rows="3" required>{{ old('short_description',$event->short_description) }}</textarea>
        </div>
        <div class="form-group"><label>Full Description * <small style="color:var(--gray-600)">(HTML supported)</small></label>
            <textarea name="full_description" class="form-control" rows="12" required>{{ old('full_description',$event->full_description) }}</textarea>
        </div>
        <div class="form-group" style="display:flex;align-items:center;gap:10px">
            <input type="checkbox" name="featured" id="featured" value="1" {{ $event->featured?'checked':'' }} style="width:18px;height:18px">
            <label for="featured" style="margin-bottom:0"><i class="fa fa-star" style="color:var(--primary-orange)"></i> Mark as Featured</label>
        </div>
        <div style="display:flex;gap:12px;margin-top:10px">
            <button type="submit" class="btn btn-orange"><i class="fa fa-save"></i>Save Changes</button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline"><i class="fa fa-arrow-left"></i>Cancel</a>
            <a href="{{ route('news.show',$event) }}" class="btn btn-primary" target="_blank"><i class="fa fa-eye"></i>Preview</a>
        </div>
    </form>
</div>
@endsection
