<form method="POST" enctype="multipart/form-data"
      action="{{ isset($download) ? route('admin.downloads.update',$download) : route('admin.downloads.store') }}">
    @csrf
    @if(isset($download)) @method('PUT') @endif
    @if($errors->any())
        <div class="alert-error">
            Please fix the errors.
            <ul style="margin:6px 0 0 18px">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif
    <div class="form-group"><label>Title *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title',$download->title??'') }}" required>
    </div>
    <div class="form-group"><label>Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description',$download->description??'') }}</textarea>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Document {{ isset($download) ? '' : '*' }}
                <small style="color:var(--gray-600)">(PDF, Word, Excel or PowerPoint, max 20MB)</small>
            </label>
            <input type="file" name="file" class="form-control"
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" {{ isset($download) ? '' : 'required' }}>
            @isset($download)
                <small style="color:var(--gray-600);display:block;margin-top:6px">
                    Current file:
                    <a href="{{ asset('docs/'.$download->filename) }}" target="_blank">{{ $download->filename }}</a>
                    &mdash; leave empty to keep it.
                </small>
            @endisset
        </div>
        <div class="form-group"><label>Category *</label>
            @php $selectedCategory = old('category', $download->category ?? \App\Models\Download::CATEGORIES[0]); @endphp
            <select name="category" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group"><label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order',$download->sort_order??0) }}">
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:5px">
            <div style="display:flex;align-items:center;gap:10px">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', isset($download) ? ($download->is_active?'1':'') : '1') ? 'checked' : '' }} style="width:18px;height:18px">
                <label for="is_active" style="margin-bottom:0">Active</label>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:12px;margin-top:10px">
        <button type="submit" class="btn btn-orange"><i class="fa fa-save"></i>{{ isset($download)?'Save Changes':'Create Entry' }}</button>
        <a href="{{ route('admin.downloads.index') }}" class="btn btn-outline"><i class="fa fa-arrow-left"></i>Cancel</a>
    </div>
</form>
