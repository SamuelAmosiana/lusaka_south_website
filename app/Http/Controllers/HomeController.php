<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Counter;
use App\Models\LearningSetting;
use App\Models\LearningFeature;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredUpdates = Event::featured()
            ->with('images')
            ->orderBy('date', 'desc')
            ->take(3)
            ->get();

        $counters = Counter::active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $learningSettings = LearningSetting::current();
        $learningFeatures = LearningFeature::active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('home', compact(
            'featuredUpdates',
            'counters',
            'learningSettings',
            'learningFeatures'
        ));
    }
}
