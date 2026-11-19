<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\School;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SchoolController extends Controller
{
    public function index()
    {
        $settings = SchoolSetting::current();
        $schools = School::ordered()->withCount([
            'programmes',
            'programmes as active_programmes_count' => fn ($q) => $q->where('is_active', true),
        ])->get();

        return view('admin.schools.index', compact('settings', 'schools'));
    }

    public function updateSettings(Request $request)
    {
        $settings = SchoolSetting::current();

        $data = $request->validate([
            'header_title'     => 'required|string|max:255',
            'header_subtitle'  => 'nullable|string|max:255',
            'section_title'    => 'required|string|max:255',
            'section_subtitle' => 'nullable|string|max:1000',
            'catalog_file'     => 'nullable|file|mimes:pdf|max:20480',
        ]);

        if ($request->hasFile('catalog_file')) {
            $file = $request->file('catalog_file');
            $name = 'programmes-catalog-' . time() . '.pdf';
            $file->move(public_path('docs'), $name);
            $data['catalog_pdf'] = 'docs/' . $name;
        }

        unset($data['catalog_file']);
        $settings->update($data);

        return redirect()->route('admin.schools.index')->with('success', 'Schools page text updated successfully.');
    }

    public function batchUpdate(Request $request)
    {
        $rows = $request->validate([
            'schools'              => 'array',
            'schools.*.sort_order' => 'nullable|integer|min:0',
            'schools.*.is_active'  => 'nullable|boolean',
        ])['schools'] ?? [];

        foreach (School::whereIn('id', array_keys($rows))->get() as $school) {
            $school->update([
                'sort_order' => (int) ($rows[$school->id]['sort_order'] ?? $school->sort_order),
                'is_active'  => (bool) ($rows[$school->id]['is_active'] ?? false),
            ]);
        }

        return redirect()->route('admin.schools.index')->with('success', 'Order and visibility saved.');
    }

    public function create()
    {
        $school = new School(['icon' => 'fa-graduation-cap', 'apply_link' => 'undergraduate', 'is_active' => true]);

        return view('admin.schools.form', compact('school'));
    }

    public function store(Request $request)
    {
        $school = new School();
        $this->save($request, $school);

        return redirect()->route('admin.schools.edit', $school)->with('success', '"' . $school->name . '" added successfully.');
    }

    public function edit(School $school)
    {
        $school->load('programmes');

        return view('admin.schools.form', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $this->save($request, $school);

        return redirect()->route('admin.schools.edit', $school)->with('success', '"' . $school->name . '" saved successfully.');
    }

    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('admin.schools.index')->with('success', '"' . $school->name . '" and its programmes were deleted.');
    }

    /** Save the school and its programme list (add, edit, reorder, hide, remove) in one go. */
    private function save(Request $request, School $school): void
    {
        $data = $request->validate([
            'name'                      => 'required|string|max:255',
            'icon'                      => ['required', 'string', 'max:50', 'regex:/^fa-[a-z0-9-]+$/'],
            'intro'                     => 'nullable|string|max:1000',
            'apply_link'                => ['required', Rule::in(array_keys(School::applyOptions()))],
            'sort_order'                => 'nullable|integer|min:0',
            'programmes'                => 'array',
            'programmes.*.id'           => 'nullable|integer',
            'programmes.*.name'         => 'nullable|string|max:255',
            'programmes.*.level'        => ['nullable', Rule::in(Programme::LEVELS)],
            'programmes.*.sort_order'   => 'nullable|integer|min:0',
            'programmes.*.is_active'    => 'nullable|boolean',
            'programmes.*.remove'       => 'nullable|boolean',
        ], [
            'icon.regex' => 'The icon must be a Font Awesome class such as fa-briefcase.',
        ]);

        DB::transaction(function () use ($request, $school, $data) {
            $school->fill([
                'name'       => $data['name'],
                'icon'       => $data['icon'],
                'intro'      => $data['intro'] ?? null,
                'apply_link' => $data['apply_link'],
                'sort_order' => $data['sort_order'] ?? ($school->exists ? $school->sort_order : School::max('sort_order') + 1),
                'is_active'  => $request->boolean('is_active'),
            ])->save();

            $existing = $school->programmes()->get()->keyBy('id');

            foreach (array_values($data['programmes'] ?? []) as $i => $row) {
                $programme = isset($row['id']) ? $existing->get((int) $row['id']) : null;
                $name = trim($row['name'] ?? '');

                if (! empty($row['remove'])) {
                    $programme?->delete();
                    continue;
                }
                if ($name === '') {
                    continue; // empty "new programme" rows; existing ones are only removed via the Remove tick
                }

                ($programme ?? new Programme(['school_id' => $school->id]))->fill([
                    'name'       => $name,
                    'level'      => $row['level'] ?? 'Certificate',
                    'sort_order' => isset($row['sort_order']) && $row['sort_order'] !== '' ? (int) $row['sort_order'] : $i + 1,
                    'is_active'  => ! empty($row['is_active']),
                ])->save();
            }
        });
    }
}
