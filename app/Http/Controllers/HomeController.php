<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Counter;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredUpdates = Event::featured()
            ->orderBy('date', 'desc')
            ->take(3)
            ->get();

        $counters = Counter::active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('home', compact('featuredUpdates', 'counters'));
    }
}
