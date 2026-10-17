<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningSetting;
use App\Models\LearningFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LearningController extends Controller
{
    public function index()
    {
        $settings = LearningSetting::current();
        $features = LearningFeature::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.learning.index', compact('settings', 'features'));
    }

    public function updateSettings(Request $request)
    {
        $settings = LearningSetting::current();

        $data = $request->validate([
            'section_title'     => 'required|string|max:200',
            'section_subtitle'  => 'nullable|string|max:500',
            'video_title'       => 'required|string|max:150',
            'video_description' => 'nullable|string|max:500',
            'video_path'        => 'nullable|string|max:255',
            'poster_image'      => 'nullable|string|max:255',
            'video_file'        => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200',
            'poster_file'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active'         => 'nullable|boolean',
        ]);

        // Handle Video File Upload
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $dir = public_path('images');
            $ext = strtolower($file->getClientOriginalExtension());
            $name = 'campus_video_' . time() . '.' . $ext;
            $file->move($dir, $name);
            $data['video_path'] = 'images/' . $name;
        } elseif (empty($data['video_path'])) {
            $data['video_path'] = $settings->video_path;
        }

        // Handle Poster Image Upload
        if ($request->hasFile('poster_file')) {
            $file = $request->file('poster_file');
            $dir = public_path('images');
            $ext = strtolower($file->getClientOriginalExtension());
            $name = 'video_poster_' . time() . '.' . $ext;
            $file->move($dir, $name);
            $data['poster_image'] = 'images/' . $name;
        }

        $data['is_active'] = $request->boolean('is_active');

        unset($data['video_file'], $data['poster_file']);

        $settings->update($data);

        return redirect()->route('admin.learning.index')
            ->with('success', 'Section settings and video updated successfully.');
    }

    public function createFeature()
    {
        return view('admin.learning.create');
    }

    public function storeFeature(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'required|string',
            'icon'        => 'required|string|max:50',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? (LearningFeature::max('sort_order') + 1);

        LearningFeature::create($data);

        return redirect()->route('admin.learning.index')
            ->with('success', 'Learning mode item created successfully.');
    }

    public function editFeature(LearningFeature $feature)
    {
        return view('admin.learning.edit', compact('feature'));
    }

    public function updateFeature(Request $request, LearningFeature $feature)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'required|string',
            'icon'        => 'required|string|max:50',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $feature->update($data);

        return redirect()->route('admin.learning.index')
            ->with('success', 'Learning mode item updated successfully.');
    }

    public function batchUpdateFeatures(Request $request)
    {
        $items = $request->input('features', []);

        foreach ($items as $id => $data) {
            $feature = LearningFeature::find($id);
            if ($feature) {
                $feature->update([
                    'title'       => $data['title'] ?? $feature->title,
                    'description' => $data['description'] ?? $feature->description,
                    'icon'        => $data['icon'] ?? $feature->icon,
                    'sort_order'  => isset($data['sort_order']) ? (int) $data['sort_order'] : $feature->sort_order,
                    'is_active'   => isset($data['is_active']) ? (bool) $data['is_active'] : false,
                ]);
            }
        }

        return redirect()->route('admin.learning.index')
            ->with('success', 'All learning mode items updated successfully.');
    }

    public function destroyFeature(LearningFeature $feature)
    {
        $feature->delete();

        return redirect()->route('admin.learning.index')
            ->with('success', 'Learning mode item deleted successfully.');
    }
}
