<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('date', 'desc')->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'date'              => 'required|date',
            'category'          => 'required|in:Latest News,Upcoming Event,Past Event,Job Vacancy',
            'author'            => 'nullable|string|max:100',
            'short_description' => 'required|string',
            'full_description'  => 'required|string',
            'featured'          => 'nullable|boolean',
        ]);

        $data['featured'] = $request->boolean('featured');
        $data['author']   = $data['author'] ?? auth()->user()->name;

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'date'              => 'required|date',
            'category'          => 'required|in:Latest News,Upcoming Event,Past Event,Job Vacancy',
            'author'            => 'nullable|string|max:100',
            'short_description' => 'required|string',
            'full_description'  => 'required|string',
            'featured'          => 'nullable|boolean',
        ]);

        $data['featured'] = $request->boolean('featured');
        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function show(Event $event)
    {
        return redirect()->route('admin.events.edit', $event);
    }
}
