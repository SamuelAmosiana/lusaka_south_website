<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DownloadController extends Controller
{
    public function index()
    {
        $downloads = Download::orderBy('sort_order')->paginate(20);
        return view('admin.downloads.index', compact('downloads'));
    }

    public function create()
    {
        return view('admin.downloads.create', ['categories' => $this->categories()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['filename']  = $this->storeFile($request->file('file'));
        $data['is_active'] = $request->boolean('is_active', true);
        Download::create($data);
        return redirect()->route('admin.downloads.index')->with('success', 'Download entry created.');
    }

    public function edit(Download $download)
    {
        return view('admin.downloads.edit', [
            'download'   => $download,
            'categories' => $this->categories($download),
        ]);
    }

    public function update(Request $request, Download $download)
    {
        $data = $this->validated($request, $download);
        if ($request->hasFile('file')) {
            $data['filename'] = $this->storeFile($request->file('file'));
        }
        $data['is_active'] = $request->boolean('is_active');
        $download->update($data);
        return redirect()->route('admin.downloads.index')->with('success', 'Download entry updated.');
    }

    public function destroy(Download $download)
    {
        $download->delete();
        return redirect()->route('admin.downloads.index')->with('success', 'Download deleted.');
    }

    public function show(Download $download)
    {
        return redirect()->route('admin.downloads.edit', $download);
    }

    /**
     * Predefined categories, plus the entry's current one so legacy values aren't lost on edit.
     */
    private function categories(?Download $download = null): array
    {
        $categories = Download::CATEGORIES;
        if ($download?->category && ! in_array($download->category, $categories, true)) {
            $categories[] = $download->category;
        }
        return $categories;
    }

    private function validated(Request $request, ?Download $download = null): array
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => [$download ? 'nullable' : 'required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx', 'max:20480'],
            'category'    => ['required', 'string', Rule::in($this->categories($download))],
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);
        unset($data['file']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        return $data;
    }

    /**
     * Move the upload into public/docs/ under a URL-safe name, never overwriting an existing file.
     */
    private function storeFile(UploadedFile $file): string
    {
        $dir       = public_path('docs');
        $extension = strtolower($file->getClientOriginalExtension());
        $base      = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'document';

        $filename = "{$base}.{$extension}";
        for ($i = 2; file_exists("{$dir}/{$filename}"); $i++) {
            $filename = "{$base}-{$i}.{$extension}";
        }

        $file->move($dir, $filename);
        return $filename;
    }
}
