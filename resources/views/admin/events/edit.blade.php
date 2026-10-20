@extends('layouts.admin')
@section('title', 'Edit Event')
@section('page-title', 'Edit News/Event')
@section('content')
<div class="card">
    <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if($errors->any())
        <div class="alert-error" style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:20px;border-left:4px solid #ef4444">
            <i class="fa fa-triangle-exclamation me-2"></i>Please fix the errors below.
            <ul style="margin:8px 0 0 20px;font-size:.9rem">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Basic Information --}}
        <div class="form-row">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                @error('title')<small style="color:#dc3545">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label>Date *</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $event->date ? $event->date->format('Y-m-d') : '') }}" required>
                @error('date')<small style="color:#dc3545">{{ $message }}</small>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Category *</label>
                <select name="category" class="form-control" required>
                    @foreach(['Latest News','Upcoming Event','Past Event','Job Vacancy'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $event->category)===$cat?'selected':'' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category')<small style="color:#dc3545">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label>Author</label>
                <input type="text" name="author" class="form-control" value="{{ old('author', $event->author) }}">
                @error('author')<small style="color:#dc3545">{{ $message }}</small>@enderror
            </div>
        </div>

        {{-- Section 1: Event Cover Image --}}
        <div class="form-section" style="background:#f8fafc;padding:22px;border-radius:12px;margin:25px 0;border:1px solid #e2e8f0">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                <div style="width:36px;height:36px;border-radius:8px;background:rgba(255,140,0,.15);display:flex;align-items:center;justify-content:center;color:var(--primary-orange)">
                    <i class="fa fa-image fa-lg"></i>
                </div>
                <div>
                    <h3 style="font-size:1.15rem;margin:0;color:var(--primary-green)">Event Cover Image (Homepage &amp; Card Display)</h3>
                    <p style="color:var(--gray-600);font-size:.85rem;margin:2px 0 0">
                        This image appears as the main visual on the homepage updates cards, news listings, and event header banner.
                    </p>
                </div>
            </div>

            <div style="display:flex;gap:25px;align-items:flex-start;flex-wrap:wrap;margin-top:15px">
                {{-- Current cover photo --}}
                @if(!empty($event->image) && file_exists(public_path($event->image)))
                <div style="width:240px;border-radius:10px;border:1px solid #cbd5e1;overflow:hidden;background:#fff;box-shadow:0 4px 10px rgba(0,0,0,.06)">
                    <div style="height:140px;overflow:hidden;position:relative">
                        <img src="{{ asset($event->image) }}" alt="Current Cover" style="width:100%;height:100%;object-fit:cover">
                        <span style="position:absolute;top:8px;left:8px;background:var(--primary-green);color:#fff;font-size:11px;padding:2px 8px;border-radius:4px;font-weight:600">Current Cover</span>
                    </div>
                    <div style="padding:10px 12px;background:#f1f5f9;border-top:1px solid #e2e8f0;display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="remove_cover" id="remove_cover" value="1" style="width:16px;height:16px;cursor:pointer">
                        <label for="remove_cover" style="margin-bottom:0;font-size:.85rem;color:#b91c1c;cursor:pointer;font-weight:600">
                            <i class="fa fa-trash me-1"></i> Remove Cover
                        </label>
                    </div>
                </div>
                @endif

                {{-- Replace or upload --}}
                <div style="flex:1;min-width:280px">
                    <label style="font-size:.9rem;font-weight:600;margin-bottom:6px;display:block">
                        {{ !empty($event->image) ? 'Replace Cover Photo' : 'Upload Cover Photo' }}
                    </label>
                    <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewCoverImage(this)">
                    <small style="color:var(--gray-600);display:block;margin-top:5px">Supported formats: JPG, PNG, WEBP (Max 10MB). Recommended aspect ratio: 16:9.</small>
                    @error('cover_image')<small style="color:#dc3545;display:block;margin-top:4px">{{ $message }}</small>@enderror

                    <div id="cover_preview_wrapper" style="display:none;width:240px;height:140px;border-radius:10px;border:2px dashed var(--primary-green);overflow:hidden;position:relative;background:#fff;margin-top:12px;box-shadow:0 4px 12px rgba(0,0,0,.08)">
                        <img id="cover_preview_img" src="" alt="New Cover Preview" style="width:100%;height:100%;object-fit:cover">
                        <span style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.65);color:#fff;font-size:11px;text-align:center;padding:3px">New Cover Preview</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Event Photo Gallery & Captions --}}
        <div class="form-section" style="background:#f8fafc;padding:22px;border-radius:12px;margin:25px 0;border:1px solid #e2e8f0">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                <div style="width:36px;height:36px;border-radius:8px;background:rgba(34,139,34,.15);display:flex;align-items:center;justify-content:center;color:var(--primary-green)">
                    <i class="fa fa-images fa-lg"></i>
                </div>
                <div>
                    <h3 style="font-size:1.15rem;margin:0;color:var(--primary-green)">Event Photo Gallery &amp; Captions</h3>
                    <p style="color:var(--gray-600);font-size:.85rem;margin:2px 0 0">
                        Manage existing pictures and captions, or add more photos to this event.
                    </p>
                </div>
            </div>

            {{-- Existing Photos List --}}
            @if($event->images->isNotEmpty())
            <div style="margin:20px 0">
                <label style="font-size:.85rem;font-weight:700;color:var(--gray-800);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;display:block">
                    Current Gallery Photos ({{ $event->images->count() }})
                </label>
                <div style="display:flex;flex-direction:column;gap:12px">
                    @foreach($event->images as $img)
                    <div style="background:#ffffff;border:1px solid #cbd5e1;border-radius:10px;padding:14px;display:flex;gap:16px;align-items:center;box-shadow:0 2px 5px rgba(0,0,0,.03)">
                        <div style="width:100px;height:80px;border-radius:8px;overflow:hidden;flex-shrink:0;background:#f1f5f9;border:1px solid #e2e8f0">
                            <img src="{{ asset($img->image_path) }}" alt="" style="width:100%;height:100%;object-fit:cover">
                        </div>
                        <div style="flex:1">
                            <label style="font-size:.85rem;font-weight:600;margin-bottom:4px;display:block">Photo Caption / Note</label>
                            <input type="text" name="existing_captions[{{ $img->id }}]" value="{{ old('existing_captions.'.$img->id, $img->caption) }}" class="form-control" placeholder="Describe this photo..." style="font-size:.85rem;padding:8px 12px">
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;background:#fee2e2;padding:8px 12px;border-radius:8px;flex-shrink:0">
                            <input type="checkbox" name="delete_images[]" id="del_img_{{ $img->id }}" value="{{ $img->id }}" style="width:16px;height:16px;cursor:pointer">
                            <label for="del_img_{{ $img->id }}" style="margin-bottom:0;color:#b91c1c;font-size:.85rem;font-weight:600;cursor:pointer">Delete</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Multi-photo Dropzone / Selector for New Photos --}}
            <label style="font-size:.85rem;font-weight:700;color:var(--gray-800);text-transform:uppercase;letter-spacing:.5px;margin:20px 0 6px;display:block">
                Add More Event Photos
            </label>
            <div id="gallery_dropzone" style="border:2px dashed #94a3b8;border-radius:12px;padding:26px 20px;text-align:center;background:#ffffff;margin-bottom:15px;transition:all .2s ease;cursor:pointer" onclick="document.getElementById('bulk_gallery_input').click()">
                <input type="file" id="bulk_gallery_input" multiple accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none" onchange="handleBulkFiles(this.files)">
                <div style="width:50px;height:50px;border-radius:50%;background:rgba(255,140,0,.12);color:var(--primary-orange);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:22px">
                    <i class="fa fa-cloud-arrow-up"></i>
                </div>
                <div style="font-weight:700;color:var(--gray-800);font-size:1.02rem;margin-bottom:4px">
                    Click to Choose Photos or Drag &amp; Drop Here
                </div>
                <p style="color:var(--gray-600);font-size:.85rem;margin:0 0 12px">
                    You can select <strong>more than one photo at once</strong> (hold <kbd style="background:#e2e8f0;padding:2px 6px;border-radius:4px">Ctrl</kbd> or <kbd style="background:#e2e8f0;padding:2px 6px;border-radius:4px">Shift</kbd> to pick several). Formats: JPG, PNG, WEBP.
                </p>
                <button type="button" class="btn btn-primary" style="padding:8px 22px;font-size:.88rem;pointer-events:none">
                    <i class="fa fa-folder-open me-2"></i> Browse &amp; Select Multiple Photos
                </button>
            </div>

            {{-- Container for New Gallery Photos with Captions --}}
            <div id="gallery_container" style="display:flex;flex-direction:column;gap:12px">
                <!-- New photo rows dynamically appended here -->
            </div>

            <div id="gallery_add_more_bar" style="margin-top:14px;display:none;justify-content:flex-end">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('bulk_gallery_input').click()" style="font-size:.85rem;padding:7px 16px">
                    <i class="fa fa-plus me-1"></i> Add More Photos to Batch
                </button>
            </div>
        </div>

        {{-- Descriptions --}}
        <div class="form-group">
            <label>Short Description * <small style="color:var(--gray-600)">(shown on cards and news listing)</small></label>
            <textarea name="short_description" class="form-control" rows="3" required>{{ old('short_description', $event->short_description) }}</textarea>
            @error('short_description')<small style="color:#dc3545">{{ $message }}</small>@enderror
        </div>

        <div class="form-group">
            <label>Full Description * <small style="color:var(--gray-600)">(HTML formatting supported)</small></label>
            <textarea name="full_description" class="form-control" rows="12" required>{{ old('full_description', $event->full_description) }}</textarea>
            @error('full_description')<small style="color:#dc3545">{{ $message }}</small>@enderror
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:10px;margin-top:15px">
            <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured', $event->featured) ? 'checked' : '' }} style="width:18px;height:18px;cursor:pointer">
            <label for="featured" style="margin-bottom:0;cursor:pointer"><i class="fa fa-star" style="color:var(--primary-orange)"></i> Mark as Featured (shown in homepage Latest Updates)</label>
        </div>

        <div style="display:flex;gap:12px;margin-top:25px;padding-top:15px;border-top:1px solid #e2e8f0">
            <button type="submit" class="btn btn-orange" style="padding:12px 28px"><i class="fa fa-save me-1"></i> Save Changes</button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline" style="padding:12px 24px"><i class="fa fa-arrow-left me-1"></i> Cancel</a>
            <a href="{{ route('news.show', $event) }}" class="btn btn-primary" target="_blank" style="padding:12px 24px"><i class="fa fa-eye me-1"></i> Preview on Website</a>
        </div>
    </form>
</div>

<script>
function previewCoverImage(input) {
    const wrapper = document.getElementById('cover_preview_wrapper');
    const img = document.getElementById('cover_preview_img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            wrapper.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        wrapper.style.display = 'none';
        img.src = '';
    }
}

let galleryRowIndex = 0;

function handleBulkFiles(files) {
    if (!files || files.length === 0) return;

    const container = document.getElementById('gallery_container');
    const addMoreBar = document.getElementById('gallery_add_more_bar');
    if (addMoreBar) addMoreBar.style.display = 'flex';

    Array.from(files).forEach((file) => {
        if (!file.type.match(/^image\//)) {
            alert('File "' + file.name + '" is not an image. Only JPG, PNG, WEBP are accepted.');
            return;
        }

        const rowId = 'gallery_item_' + galleryRowIndex++;
        const row = document.createElement('div');
        row.id = rowId;
        row.className = 'gallery-item-card';
        row.style.cssText = 'background:#ffffff;border:1px solid #cbd5e1;border-radius:10px;padding:14px;display:flex;gap:16px;align-items:center;box-shadow:0 2px 6px rgba(0,0,0,.04);transition:all .2s ease';

        const sizeFormatted = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

        row.innerHTML = `
            <div style="width:110px;height:85px;border-radius:8px;overflow:hidden;flex-shrink:0;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;position:relative">
                <img id="${rowId}_img" src="" alt="${file.name}" style="width:100%;height:100%;object-fit:cover">
            </div>
            <div style="flex:1">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                    <span style="font-size:.85rem;font-weight:600;color:var(--gray-800);word-break:break-all">
                        <i class="fa fa-image me-1" style="color:var(--primary-orange)"></i> ${escapeHtml(file.name)}
                    </span>
                    <span style="font-size:.75rem;background:#f1f5f9;color:var(--gray-600);padding:2px 8px;border-radius:10px;font-weight:600">
                        ${sizeFormatted}
                    </span>
                </div>
                <input type="text" name="gallery_captions[]" class="form-control" placeholder="Caption for this photo (e.g. Students demonstrating project, Keynote speaker, etc.)" style="font-size:.88rem;padding:8px 12px">
            </div>
            <button type="button" onclick="removeGalleryItem('${rowId}')" title="Remove photo" style="background:#fee2e2;color:#ef4444;border:none;width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;transition:background .2s ease">
                <i class="fa fa-trash"></i>
            </button>
        `;

        // Store file directly into a hidden input with DataTransfer
        const dt = new DataTransfer();
        dt.items.add(file);

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'file';
        hiddenInput.name = 'gallery_images[]';
        hiddenInput.style.display = 'none';
        hiddenInput.files = dt.files;
        row.appendChild(hiddenInput);

        // Preview thumbnail
        const imgElem = row.querySelector(`#${rowId}_img`);
        imgElem.src = URL.createObjectURL(file);

        container.appendChild(row);
    });

    document.getElementById('bulk_gallery_input').value = '';
}

function removeGalleryItem(rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
    }
    const container = document.getElementById('gallery_container');
    const addMoreBar = document.getElementById('gallery_add_more_bar');
    if (container.children.length === 0 && addMoreBar) {
        addMoreBar.style.display = 'none';
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Drag & drop support
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('gallery_dropzone');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.borderColor = 'var(--primary-orange)';
                dropzone.style.background = 'rgba(255,140,0,.05)';
            }, false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.borderColor = '#94a3b8';
                dropzone.style.background = '#ffffff';
            }, false);
        });
        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleBulkFiles(files);
        }, false);
    }
});
</script>
@endsection
