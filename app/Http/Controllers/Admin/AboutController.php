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
        $pages = AboutPage::whereIn('slug', AboutPage::aboutSlugs())->withCount('items')->get()->keyBy('slug');

        return view('admin.about.index', compact('pages'));
    }

    public function edit(AboutPage $page)
    {
        $page->load('items');

        return view('admin.about.edit', compact('page'));
    }

    public function updatePage(Request $request, AboutPage $page)
    {
        // Only the fields this page/section actually shows can be changed
        $rules = [];
        foreach ($page->fields() as $field => $spec) {
            if (! empty($spec['image'])) {
                $rules['image_file'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120';
                continue;
            }
            $max = in_array($field, ['section_subtitle', 'extra_text']) || ! empty($spec['textarea']) ? 3000 : 255;
            $rules[$field] = [! empty($spec['required']) ? 'required' : 'nullable', 'string', 'max:' . $max];
        }
        if ($extra = $page->config()['extra']) {
            $rules['extra_title'] = 'nullable|string|max:255';
            if ($extra['text']) {
                $rules['extra_text'] = 'nullable|string|max:3000';
            }
        }

        $data = $request->validate($rules);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $name = $page->slug . '_' . time() . '.' . strtolower($file->getClientOriginalExtension());
            $file->move(public_path('images/about'), $name);
            $this->deleteUploadedImage($page->image);
            $data['image'] = 'images/about/' . $name;
        }
        unset($data['image_file']);

        $page->update($data);

        return redirect()->route('admin.about.edit', $page)->with('success', 'Text updated successfully.');
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
        $spec = $item->fieldSpec();
        $required = fn (string $field) => ! empty($spec[$field][1]);

        $rules = [
            'title'      => [$required('title') ? 'required' : 'nullable', 'string', 'max:255'],
            'subtitle'   => [Rule::requiredIf($required('subtitle')), 'nullable', 'string', 'max:255'],
            'value'      => [Rule::requiredIf($required('value')), 'nullable', 'string', 'max:50'],
            'body'       => [Rule::requiredIf($required('body')), 'nullable', 'string', 'max:3000'],
            'icon'       => [Rule::requiredIf($required('icon')), 'nullable', 'string', 'max:50', 'regex:/^fa-[a-z0-9-]+$/'],
            'url'        => [Rule::requiredIf($required('url')), 'nullable', 'url:http,https', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:50', 'regex:/^[0-9+()\-\s\/]+$/'],
            'email'      => 'nullable|email|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ];
        if (isset($spec['image'])) {
            // A picture is required for slides and photos, unless one is already saved
            $mustUpload = $required('image') && ! $item->image;
            $rules['image_file'] = [$mustUpload ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'];
            $rules['remove_image'] = 'nullable|boolean';
        }

        $data = $request->validate($rules, [
            'icon.regex'          => 'The icon must be a Font Awesome class such as fa-star.',
            'url.url'             => 'The link must be a full web address starting with http:// or https://',
            'phone.regex'         => 'The phone number may only contain digits, spaces and + - ( ) /',
            'image_file.required' => 'Please choose a picture.',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['title'] = $data['title'] ?? '';

        // Clear fields that this kind of item does not use
        foreach (['subtitle', 'value', 'body', 'icon', 'url', 'phone', 'email'] as $field) {
            if (! isset($spec[$field])) {
                $data[$field] = null;
            }
        }

        if (isset($spec['image'])) {
            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $name = $item->group . '_' . time() . '_' . mt_rand(100, 999) . '.' . strtolower($file->getClientOriginalExtension());
                $file->move(public_path('images/about'), $name);
                $data['image'] = 'images/about/' . $name;
            } elseif ($request->boolean('remove_image') && ! $required('image')) {
                $data['image'] = null;
            }
        } else {
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
