<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutItem;
use App\Models\AboutPage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AboutController extends Controller
{
    public function index()
    {
        $pages = AboutPage::withCount('items')->get()->keyBy('slug');

        return view('admin.about.index', compact('pages'));
    }

    public function edit(AboutPage $page)
    {
        $page->load('items');

        return view('admin.about.edit', compact('page'));
    }

    public function updatePage(Request $request, AboutPage $page)
    {
        $data = $request->validate([
            'header_title'     => 'required|string|max:255',
            'header_subtitle'  => 'nullable|string|max:255',
            'section_title'    => 'required|string|max:255',
            'section_subtitle' => 'nullable|string|max:1000',
            'extra_title'      => 'nullable|string|max:255',
            'extra_text'       => 'nullable|string|max:3000',
        ]);

        if (! $page->config()['extra']) {
            unset($data['extra_title'], $data['extra_text']);
        }

        $page->update($data);

        return redirect()->route('admin.about.edit', $page)->with('success', 'Page text updated successfully.');
    }

    public function createItem(AboutPage $page, string $group)
    {
        abort_unless(isset($page->config()['groups'][$group]), 404);

        $item = new AboutItem(['page' => $page->slug, 'group' => $group, 'is_active' => true]);

        return view('admin.about.item', compact('page', 'item'));
    }

    public function storeItem(Request $request, AboutPage $page, string $group)
    {
        abort_unless(isset($page->config()['groups'][$group]), 404);

        $item = new AboutItem(['page' => $page->slug, 'group' => $group]);
        $data = $this->validateItem($request, $item);
        $data['sort_order'] = $data['sort_order']
            ?? (AboutItem::where('page', $page->slug)->where('group', $group)->max('sort_order') + 1);

        $item->fill($data)->save();

        return redirect()->route('admin.about.edit', $page)->with('success', '"' . $item->title . '" added successfully.');
    }

    public function editItem(AboutItem $item)
    {
        $page = $item->aboutPage;

        return view('admin.about.item', compact('page', 'item'));
    }

    public function updateItem(Request $request, AboutItem $item)
    {
        $oldImage = $item->image;
        $item->update($this->validateItem($request, $item));

        if ($oldImage && $oldImage !== $item->image) {
            $this->deleteUploadedImage($oldImage);
        }

        return redirect()->route('admin.about.edit', $item->page)->with('success', '"' . $item->title . '" updated successfully.');
    }

    public function batchUpdate(Request $request, AboutPage $page)
    {
        $rows = $request->validate([
            'items'              => 'array',
            'items.*.sort_order' => 'nullable|integer|min:0',
            'items.*.is_active'  => 'nullable|boolean',
        ])['items'] ?? [];

        foreach ($page->items()->whereIn('id', array_keys($rows))->get() as $item) {
            $item->update([
                'sort_order' => (int) ($rows[$item->id]['sort_order'] ?? $item->sort_order),
                'is_active'  => (bool) ($rows[$item->id]['is_active'] ?? false),
            ]);
        }

        return redirect()->route('admin.about.edit', $page)->with('success', 'Order and visibility saved.');
    }

    public function destroyItem(AboutItem $item)
    {
        $page = $item->page;
        $this->deleteUploadedImage($item->image);
        $item->delete();

        return redirect()->route('admin.about.edit', $page)->with('success', '"' . $item->title . '" deleted.');
    }

    private function validateItem(Request $request, AboutItem $item): array
    {
        $group = $item->groupConfig();
        $isPerson = $group['type'] === 'person';

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'subtitle'     => [Rule::requiredIf($isPerson), 'nullable', 'string', 'max:255'],
            'body'         => [Rule::requiredIf(! $isPerson), 'nullable', 'string', 'max:3000'],
            'icon'         => [Rule::requiredIf(! $isPerson), 'nullable', 'string', 'max:50', 'regex:/^fa-[a-z0-9-]+$/'],
            'image_file'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_image' => 'nullable|boolean',
            'sort_order'   => 'nullable|integer|min:0',
        ], [
            'icon.regex' => 'The icon must be a Font Awesome class such as fa-star.',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($isPerson) {
            $data['icon'] = null;
            if (! ($group['describe'] ?? false)) {
                $data['body'] = null;
            }
            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $name = $item->group . '_' . time() . '_' . mt_rand(100, 999) . '.' . strtolower($file->getClientOriginalExtension());
                $file->move(public_path('images/about'), $name);
                $data['image'] = 'images/about/' . $name;
            } elseif ($request->boolean('remove_image')) {
                $data['image'] = null;
            }
        } else {
            $data['subtitle'] = null;
            $data['image'] = null;
        }

        unset($data['image_file'], $data['remove_image']);

        return $data;
    }

    /** Only delete files this module uploaded, never the original staff photos. */
    private function deleteUploadedImage(?string $path): void
    {
        if ($path && str_starts_with($path, 'images/about/') && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
