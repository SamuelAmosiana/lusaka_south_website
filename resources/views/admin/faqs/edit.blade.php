@extends('layouts.admin')
@section('title','Edit FAQ')
@section('page-title','Edit FAQ')
@section('content')
<div class="card">
    <form method="POST" action="{{ route('admin.faqs.update',$faq) }}">
        @csrf @method('PUT')
        @if($errors->any())<div class="alert-error">Please fix the errors below.</div>@endif
        <div class="form-group"><label>Question *</label>
            <input type="text" name="question" class="form-control" value="{{ old('question',$faq->question) }}" required>
        </div>
        <div class="form-group"><label>Answer *</label>
            <textarea name="answer" class="form-control" rows="5" required>{{ old('answer',$faq->answer) }}</textarea>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Category</label>
                <input type="text" name="category" class="form-control" value="{{ old('category',$faq->category) }}">
            </div>
            <div class="form-group"><label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order',$faq->sort_order) }}">
            </div>
        </div>
        <div class="form-group" style="display:flex;align-items:center;gap:10px">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ $faq->is_active?'checked':'' }} style="width:18px;height:18px">
            <label for="is_active" style="margin-bottom:0">Active</label>
        </div>
        <div style="display:flex;gap:12px;margin-top:10px">
            <button type="submit" class="btn btn-orange"><i class="fa fa-save"></i>Save Changes</button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline"><i class="fa fa-arrow-left"></i>Cancel</a>
        </div>
    </form>
</div>
@endsection
