<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index()
    {
        $counters = Counter::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.counters.index', compact('counters'));
    }

    public function create()
    {
        return view('admin.counters.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'      => 'required|string|max:100',
            'number'     => 'required|integer|min:0',
            'suffix'     => 'nullable|string|max:10',
            'icon'       => 'required|string|max:50',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? (Counter::max('sort_order') + 1);

        Counter::create($data);

        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter created successfully.');
    }

    public function edit(Counter $counter)
    {
        return view('admin.counters.edit', compact('counter'));
    }

    public function update(Request $request, Counter $counter)
    {
        $data = $request->validate([
            'label'      => 'required|string|max:100',
            'number'     => 'required|integer|min:0',
            'suffix'     => 'nullable|string|max:10',
            'icon'       => 'required|string|max:50',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $counter->update($data);

        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter updated successfully.');
    }

    public function batchUpdate(Request $request)
    {
        $items = $request->input('counters', []);

        foreach ($items as $id => $data) {
            $counter = Counter::find($id);
            if ($counter) {
                $counter->update([
                    'label'      => $data['label'] ?? $counter->label,
                    'number'     => isset($data['number']) ? (int) $data['number'] : $counter->number,
                    'suffix'     => $data['suffix'] ?? null,
                    'icon'       => $data['icon'] ?? $counter->icon,
                    'sort_order' => isset($data['sort_order']) ? (int) $data['sort_order'] : $counter->sort_order,
                    'is_active'  => isset($data['is_active']) ? (bool) $data['is_active'] : false,
                ]);
            }
        }

        return redirect()->route('admin.counters.index')
            ->with('success', 'All counters updated successfully.');
    }

    public function destroy(Counter $counter)
    {
        $counter->delete();

        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter deleted successfully.');
    }
}
