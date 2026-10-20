<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query  = Event::query()->with('images')->orderBy('date', 'desc');
        $filter = $request->get('filter', 'all');
        $search = $request->get('search', '');

        if ($filter !== 'all') {
            $query->where('category', $filter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $events = $query->get();

        $latestNews     = $events->where('category', 'Latest News');
        $upcomingEvents = $events->where('category', 'Upcoming Event');
        $pastEvents     = $events->where('category', 'Past Event');

        return view('news.index', compact(
            'events', 'latestNews', 'upcomingEvents', 'pastEvents', 'filter', 'search'
        ));
    }

    public function show(Event $event)
    {
        $event->load('images');

        $relatedEvents = Event::where('id', '!=', $event->id)
            ->where('category', $event->category)
            ->orderBy('date', 'desc')
            ->take(3)
            ->get();

        return view('news.show', compact('event', 'relatedEvents'));
    }
}
