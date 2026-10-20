<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('images')->orderBy('date', 'desc')->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'date'               => 'required|date',
            'category'           => 'required|in:Latest News,Upcoming Event,Past Event,Job Vacancy',
            'author'             => 'nullable|string|max:100',
            'short_description'  => 'required|string',
            'full_description'   => 'required|string',
            'featured'           => 'nullable|boolean',
            'cover_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'gallery_images'     => 'nullable|array',
            'gallery_images.*'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'gallery_captions'   => 'nullable|array',
            'gallery_captions.*' => 'nullable|string|max:255',
        ]);

        $data['featured'] = $request->boolean('featured');
        $data['author']   = $data['author'] ?? auth()->user()->name;

        // Handle Cover Image Upload
        if ($request->hasFile('cover_image')) {
            $coverFile = $request->file('cover_image');
            $coverName = 'cover_' . time() . '_' . Str::random(8) . '.' . strtolower($coverFile->getClientOriginalExtension());
            $coverFile->move(public_path('images/events'), $coverName);
            $data['image'] = 'images/events/' . $coverName;
        }

        $event = Event::create($data);

        // Handle Extra Gallery Images with Captions
        if ($request->hasFile('gallery_images')) {
            $galleryFiles = array_values($request->file('gallery_images'));
            $captions = array_values($request->input('gallery_captions', []));

            foreach ($galleryFiles as $index => $file) {
                if ($file && $file->isValid()) {
                    $fileName = 'event_' . $event->id . '_' . time() . '_' . $index . '_' . Str::random(6) . '.' . strtolower($file->getClientOriginalExtension());
                    $file->move(public_path('images/events'), $fileName);

                    $caption = isset($captions[$index]) ? trim($captions[$index]) : null;

                    EventImage::create([
                        'event_id'   => $event->id,
                        'image_path' => 'images/events/' . $fileName,
                        'caption'    => $caption ?: null,
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $event->load('images');
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'date'               => 'required|date',
            'category'           => 'required|in:Latest News,Upcoming Event,Past Event,Job Vacancy',
            'author'             => 'nullable|string|max:100',
            'short_description'  => 'required|string',
            'full_description'   => 'required|string',
            'featured'           => 'nullable|boolean',
            'cover_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'remove_cover'       => 'nullable|boolean',
            'gallery_images'     => 'nullable|array',
            'gallery_images.*'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'gallery_captions'   => 'nullable|array',
            'gallery_captions.*' => 'nullable|string|max:255',
            'existing_captions'  => 'nullable|array',
            'existing_captions.*'=> 'nullable|string|max:255',
            'delete_images'      => 'nullable|array',
            'delete_images.*'    => 'nullable|integer',
        ]);

        $data['featured'] = $request->boolean('featured');

        // Handle Cover Image removal or replacement
        if ($request->boolean('remove_cover')) {
            $this->deleteFileIfExists($event->image);
            $data['image'] = null;
        } elseif ($request->hasFile('cover_image')) {
            $this->deleteFileIfExists($event->image);
            $coverFile = $request->file('cover_image');
            $coverName = 'cover_' . time() . '_' . Str::random(8) . '.' . strtolower($coverFile->getClientOriginalExtension());
            $coverFile->move(public_path('images/events'), $coverName);
            $data['image'] = 'images/events/' . $coverName;
        }

        $event->update($data);

        // Update Existing Gallery Captions
        if ($request->has('existing_captions')) {
            foreach ($request->input('existing_captions') as $imageId => $caption) {
                EventImage::where('id', $imageId)
                    ->where('event_id', $event->id)
                    ->update(['caption' => trim($caption) ?: null]);
            }
        }

        // Delete Marked Existing Gallery Images
        if ($request->has('delete_images')) {
            $deleteIds = (array) $request->input('delete_images');
            $imagesToDelete = EventImage::whereIn('id', $deleteIds)
                ->where('event_id', $event->id)
                ->get();

            foreach ($imagesToDelete as $img) {
                $this->deleteFileIfExists($img->image_path);
                $img->delete();
            }
        }

        // Handle Newly Uploaded Gallery Images with Captions
        if ($request->hasFile('gallery_images')) {
            $galleryFiles = array_values($request->file('gallery_images'));
            $captions = array_values($request->input('gallery_captions', []));
            $currentMaxOrder = (int) $event->images()->max('sort_order');

            foreach ($galleryFiles as $index => $file) {
                if ($file && $file->isValid()) {
                    $order = $currentMaxOrder + $index + 1;
                    $fileName = 'event_' . $event->id . '_' . time() . '_' . $index . '_' . Str::random(6) . '.' . strtolower($file->getClientOriginalExtension());
                    $file->move(public_path('images/events'), $fileName);

                    $caption = isset($captions[$index]) ? trim($captions[$index]) : null;

                    EventImage::create([
                        'event_id'   => $event->id,
                        'image_path' => 'images/events/' . $fileName,
                        'caption'    => $caption ?: null,
                        'sort_order' => $order,
                    ]);
                }
            }
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Delete Cover Image
        $this->deleteFileIfExists($event->image);

        // Delete Gallery Images
        foreach ($event->images as $img) {
            $this->deleteFileIfExists($img->image_path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function show(Event $event)
    {
        return redirect()->route('admin.events.edit', $event);
    }

    /**
     * Delete file from disk if it exists.
     */
    private function deleteFileIfExists(?string $relativePath): void
    {
        if (!empty($relativePath)) {
            $fullPath = public_path($relativePath);
            if (file_exists($fullPath) && is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}
