<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Models\GallerySection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $sections = GallerySection::ordered()->withCount([
            'photos',
            'photos as active_photos_count' => fn ($q) => $q->where('is_active', true),
        ])->with(['photos' => fn ($q) => $q->where('is_active', true)->limit(4)])->get();

        return view('admin.gallery.index', compact('sections'));
    }

    public function batchUpdate(Request $request)
    {
        $rows = $request->validate([
            'sections'              => 'array',
            'sections.*.sort_order' => 'nullable|integer|min:0',
            'sections.*.is_active'  => 'nullable|boolean',
        ])['sections'] ?? [];

        foreach (GallerySection::whereIn('id', array_keys($rows))->get() as $section) {
            $section->update([
                'sort_order' => (int) ($rows[$section->id]['sort_order'] ?? $section->sort_order),
                'is_active'  => (bool) ($rows[$section->id]['is_active'] ?? false),
            ]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Order and visibility saved.');
    }

    public function create()
    {
        $section = new GallerySection(['is_active' => true]);

        return view('admin.gallery.form', compact('section'));
    }

    public function store(Request $request)
    {
        $section = new GallerySection();
        $added = $this->save($request, $section);

        return redirect()->route('admin.gallery.edit', $section)
            ->with('success', '"' . $section->title . '" created' . ($added ? " with $added " . Str::plural('photo', $added) : '') . '.');
    }

    public function edit(GallerySection $gallery)
    {
        $section = $gallery->load('photos');

        return view('admin.gallery.form', compact('section'));
    }

    public function update(Request $request, GallerySection $gallery)
    {
        $added = $this->save($request, $gallery);

        return redirect()->route('admin.gallery.edit', $gallery)
            ->with('success', '"' . $gallery->title . '" saved' . ($added ? "; $added new " . Str::plural('photo', $added) . ' added' : '') . '.');
    }

    public function destroy(GallerySection $gallery)
    {
        $gallery->photos->each->deleteUploadedFile();
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', '"' . $gallery->title . '" and its photos were deleted.');
    }

    /** Save the section, its existing photos (caption, order, show, remove) and any new uploads. Returns how many photos were added. */
    private function save(Request $request, GallerySection $section): int
    {
        $data = $request->validate([
            'title'               => 'required|string|max:255',
            'subtitle'            => 'nullable|string|max:255',
            'sort_order'          => 'nullable|integer|min:0',
            'photos'              => 'array',
            'photos.*.caption'    => 'nullable|string|max:255',
            'photos.*.sort_order' => 'nullable|integer|min:0',
            'photos.*.is_active'  => 'nullable|boolean',
            'photos.*.remove'     => 'nullable|boolean',
            'new_photos'          => 'array|max:' . (int) ini_get('max_file_uploads'),
            'new_photos.*'        => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'new_captions'        => 'array',
            'new_captions.*'      => 'nullable|string|max:255',
        ], [
            'new_photos.*.image' => 'Each new photo must be an image (JPG, PNG or WebP).',
            'new_photos.*.mimes' => 'Each new photo must be a JPG, PNG or WebP file.',
            'new_photos.*.max'   => 'Each new photo must be 5MB or smaller.',
        ]);

        return DB::transaction(function () use ($request, $section, $data) {
            $section->fill([
                'title'      => $data['title'],
                'subtitle'   => $data['subtitle'] ?? null,
                'sort_order' => $data['sort_order'] ?? ($section->exists ? $section->sort_order : GallerySection::max('sort_order') + 1),
                'is_active'  => $request->boolean('is_active'),
            ])->save();

            // Existing photos: only those belonging to this section can be touched
            foreach ($section->photos()->whereIn('id', array_keys($data['photos'] ?? []))->get() as $photo) {
                $row = $data['photos'][$photo->id];
                if (! empty($row['remove'])) {
                    $photo->deleteUploadedFile();
                    $photo->delete();
                    continue;
                }
                $photo->update([
                    'caption'    => $row['caption'] ?? null,
                    'sort_order' => isset($row['sort_order']) ? (int) $row['sort_order'] : $photo->sort_order,
                    'is_active'  => ! empty($row['is_active']),
                ]);
            }

            $added = 0;
            $next = (int) $section->photos()->max('sort_order');
            foreach ($request->file('new_photos', []) as $i => $file) {
                $name = 'gallery_' . time() . '_' . Str::random(6) . '.' . strtolower($file->getClientOriginalExtension());
                $file->move(public_path(GalleryPhoto::UPLOAD_DIR), $name);

                $caption = trim($data['new_captions'][$i] ?? '');
                $section->photos()->create([
                    'image'      => GalleryPhoto::UPLOAD_DIR . '/' . $name,
                    // Default caption from the file name, e.g. "graduation-day.jpg" -> "Graduation Day"
                    'caption'    => $caption !== '' ? $caption : Str::headline(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
                    'sort_order' => ++$next,
                    'is_active'  => true,
                ]);
                $added++;
            }

            return $added;
        });
    }
}
