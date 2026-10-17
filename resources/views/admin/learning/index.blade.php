@extends('layouts.admin')
@section('title', 'Online & On-Campus Learning')
@section('page-title', 'Online & On-Campus Learning Management')

@section('styles')
<style>
.section-preview-box {
    background: #fff;
    border: 2px solid var(--gray-200);
    border-radius: 14px;
    padding: 25px;
    margin-bottom: 25px;
}
.preview-grid-admin {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 30px;
    align-items: start;
    margin-top: 15px;
}
.preview-feature-item {
    display: flex;
    gap: 15px;
    padding: 12px 16px;
    background: var(--gray-100);
    border-radius: 10px;
    border-left: 3px solid var(--primary-green);
    margin-bottom: 12px;
}
.preview-feature-item.inactive {
    opacity: 0.5;
    border-left-color: #dc3545;
}
.preview-feature-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--primary-green);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.preview-feature-body h5 {
    font-size: 0.95rem;
    color: var(--primary-green);
    margin: 0 0 4px;
}
.preview-feature-body p {
    font-size: 0.82rem;
    color: var(--gray-600);
    margin: 0;
    line-height: 1.5;
}
.preview-video-frame {
    background: #000;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    padding-top: 56.25%;
}
.preview-video-frame video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.tab-container {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid var(--gray-200);
}
.tab-btn {
    padding: 10px 20px;
    font-weight: 600;
    border: none;
    background: none;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    color: var(--gray-600);
    font-size: 0.95rem;
}
.tab-btn.active {
    color: var(--primary-green);
    border-bottom-color: var(--primary-green);
}
@media(max-width: 900px) {
    .preview-grid-admin {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<!-- Section Live Preview -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-eye me-2"></i>Live Section Preview</h3>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View on Home Page</a>
    </div>

    <div class="section-preview-box">
        <div style="text-align: center; margin-bottom: 15px;">
            <h2 style="color: var(--primary-green); font-size: 1.6rem; margin-bottom: 5px;">{{ $settings->section_title }}</h2>
            <p style="color: var(--gray-600); font-size: 0.95rem; max-width: 600px; margin: 0 auto;">{{ $settings->section_subtitle }}</p>
        </div>

        <div class="preview-grid-admin">
            <div>
                @forelse($features as $feat)
                <div class="preview-feature-item {{ $feat->is_active ? '' : 'inactive' }}">
                    <div class="preview-feature-icon">
                        <i class="fa {{ $feat->icon }}"></i>
                    </div>
                    <div class="preview-feature-body">
                        <h5>{{ $feat->title }} @if(!$feat->is_active)<span class="badge badge-red" style="font-size:0.65rem;margin-left:5px">Inactive</span>@endif</h5>
                        <p>{{ Str::limit($feat->description, 160) }}</p>
                    </div>
                </div>
                @empty
                <p style="color:var(--gray-600)">No feature items configured yet.</p>
                @endforelse
            </div>

            <div>
                <div class="preview-video-frame">
                    <video controls playsinline preload="metadata" poster="{{ asset($settings->poster_image ?: 'images/school2.jpeg') }}">
                        <source src="{{ asset($settings->video_path) }}" type="video/mp4">
                    </video>
                </div>
                <div style="padding: 12px 15px; background: var(--gray-100); border-radius: 0 0 10px 10px; border: 1px solid var(--gray-200); border-top: none;">
                    <h5 style="margin: 0 0 4px; color: var(--gray-800);"><i class="fa fa-circle-play text-orange me-1" style="color: var(--primary-orange);"></i> {{ $settings->video_title }}</h5>
                    <p style="margin: 0; font-size: 0.82rem; color: var(--gray-600);">{{ $settings->video_description }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section & Video Settings Form -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-video me-2"></i>Section &amp; Video Settings</h3>
        <span class="badge {{ $settings->is_active ? 'badge-green' : 'badge-red' }}">
            {{ $settings->is_active ? 'Section Active on Home' : 'Section Hidden' }}
        </span>
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

    <form method="POST" action="{{ route('admin.learning.settings') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="section_title">Section Title *</label>
                <input type="text" name="section_title" id="section_title" class="form-control" value="{{ old('section_title', $settings->section_title) }}" required>
            </div>
            <div class="form-group">
                <label for="video_title">Video Caption Heading *</label>
                <input type="text" name="video_title" id="video_title" class="form-control" value="{{ old('video_title', $settings->video_title) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="section_subtitle">Section Subtitle</label>
            <textarea name="section_subtitle" id="section_subtitle" class="form-control" rows="2">{{ old('section_subtitle', $settings->section_subtitle) }}</textarea>
        </div>

        <div class="form-group">
            <label for="video_description">Video Caption Description</label>
            <textarea name="video_description" id="video_description" class="form-control" rows="2">{{ old('video_description', $settings->video_description) }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="video_file">Upload New Video File (.mp4, max 50MB)</label>
                <input type="file" name="video_file" id="video_file" class="form-control" accept="video/mp4,video/webm,video/mov">
                <small style="color:var(--gray-600)">Current file: <code>{{ $settings->video_path }}</code></small>
            </div>
            <div class="form-group">
                <label for="video_path">Or Video File Path / URL</label>
                <input type="text" name="video_path" id="video_path" class="form-control" value="{{ old('video_path', $settings->video_path) }}" placeholder="images/campus_video.mp4">
                <small style="color:var(--gray-600)">Relative to public/ or an absolute URL</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="poster_file">Video Poster Image (Thumbnail)</label>
                <input type="file" name="poster_file" id="poster_file" class="form-control" accept="image/*">
                <small style="color:var(--gray-600)">Current poster: <code>{{ $settings->poster_image }}</code></small>
            </div>
            <div class="form-group" style="display: flex; align-items: center; margin-top: 25px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $settings->is_active) ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--primary-green);">
                    <span style="font-weight: 600;">Display Section on Home Page</span>
                </label>
            </div>
        </div>

        <div style="margin-top: 15px;">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save Section &amp; Video Settings</button>
        </div>
    </form>
</div>

<!-- Learning Features List & Batch Editor -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-list-check me-2"></i>Learning Modes &amp; Feature Cards (Left Column)</h3>
        <a href="{{ route('admin.learning.features.create') }}" class="btn btn-orange"><i class="fa fa-plus"></i> Add New Mode</a>
    </div>

    <form method="POST" action="{{ route('admin.learning.features.batch') }}">
        @csrf
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">Order</th>
                    <th style="width: 60px;">Icon</th>
                    <th style="width: 200px;">Title</th>
                    <th>Description</th>
                    <th style="width: 80px; text-align: center;">Active</th>
                    <th style="width: 100px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($features as $feature)
                <tr>
                    <td>
                        <input type="number" name="features[{{ $feature->id }}][sort_order]" value="{{ $feature->sort_order }}" class="form-control" style="padding: 6px 8px; text-align: center;" min="0">
                    </td>
                    <td>
                        <div style="width: 38px; height: 38px; border-radius: 6px; background: var(--gray-100); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: var(--primary-green);">
                            <i class="fa {{ $feature->icon }}"></i>
                        </div>
                        <input type="hidden" name="features[{{ $feature->id }}][icon]" value="{{ $feature->icon }}">
                    </td>
                    <td>
                        <input type="text" name="features[{{ $feature->id }}][title]" value="{{ $feature->title }}" class="form-control" style="padding: 6px 10px; font-weight: 600;" required>
                    </td>
                    <td>
                        <textarea name="features[{{ $feature->id }}][description]" class="form-control" rows="2" style="padding: 6px 10px; font-size: 0.85rem;" required>{{ $feature->description }}</textarea>
                    </td>
                    <td style="text-align: center;">
                        <input type="hidden" name="features[{{ $feature->id }}][is_active]" value="0">
                        <input type="checkbox" name="features[{{ $feature->id }}][is_active]" value="1" {{ $feature->is_active ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary-green); cursor: pointer;">
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.learning.features.edit', $feature) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Delete {{ $feature->title }}?')) document.getElementById('delete-feature-{{ $feature->id }}').submit();" title="Delete"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--gray-600); padding: 30px;">
                        No learning features configured. <a href="{{ route('admin.learning.features.create') }}">Add one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($features->isNotEmpty())
        <div style="margin-top: 25px; display: flex; justify-content: space-between; align-items: center;">
            <p style="color: var(--gray-600); font-size: 0.85rem;">
                <i class="fa fa-circle-info me-1"></i> Changes will immediately reflect on the home page.
            </p>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i> Save All Feature Cards
            </button>
        </div>
        @endif
    </form>
</div>

@foreach($features as $feature)
<form id="delete-feature-{{ $feature->id }}" method="POST" action="{{ route('admin.learning.features.destroy', $feature) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

@endsection
