<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredUpdates = Event::featured()
            ->orderBy('date', 'desc')
            ->take(3)
            ->get();

        return view('home', compact('featuredUpdates'));
    }
}
