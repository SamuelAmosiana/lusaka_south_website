<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Download;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events'    => Event::count(),
            'upcoming_events' => Event::where('category', 'Upcoming Event')->count(),
            'total_faqs'      => Faq::count(),
            'total_downloads' => Download::count(),
        ];

        $recentEvents = Event::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentEvents'));
    }
}
