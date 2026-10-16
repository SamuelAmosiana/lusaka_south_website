@extends('layouts.admin')
@section('title', isset($faq) ? 'Edit FAQ' : 'Add FAQ')
@section('page-title', isset($faq) ? 'Edit FAQ' : 'Add FAQ')
@section('content')
<div class="card">
    <form method="POST" action="{{ isset($faq) ? route('admin.faqs.update',$faq) : route('admin.faqs.store') }}">
        @csrf
        @if(isset($faq)) @method('PUT') @endif
        @if($errors->any())<div class="alert-error">Please fix the errors below.</div>@endif
        <div class="form-group"><label>Question *</label>
            <input type="text" name="question" class="form-control" value="{{ old('question',$faq->question??'') }}" required>
        </div>
        <div class="form-group"><label>Answer *</label>
            <textarea name="answer" class="form-control" rows="5" required>{{ old('answer',$faq->answer??'') }}</textarea>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Category</label>
                <input type="text" name="category" class="form-control" value="{{ old('category',$faq->category??'General') }}" list="faq-categories">
                <datalist id="faq-categories">
                    @foreach(['General','Admissions','Financial Aid','Campus Life','Academic','Career Services','Financial'] as $cat)
                    <option value="{{ $cat }}">
                    @endforeach
                </datalist>
            </div>
            <div class="form-group"><label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order',$faq->sort_order??0) }}">
            </div>
        </div>
        <div class="form-group" style="display:flex;align-items:center;gap:10px">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', isset($faq) ? ($faq->is_active?'1':'') : '1') ? 'checked' : '' }} style="width:18px;height:18px">
            <label for="is_active" style="margin-bottom:0">Active (visible on site)</label>
        </div>
        <div style="display:flex;gap:12px;margin-top:10px">
            <button type="submit" class="btn btn-orange"><i class="fa fa-save"></i>{{ isset($faq)?'Save Changes':'Create FAQ' }}</button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline"><i class="fa fa-arrow-left"></i>Cancel</a>
        </div>
    </form>
</div>
@endsection
