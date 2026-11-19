<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
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

        // Editable home page sections (Admin > Home Page)
        $home = AboutPage::with('items')->whereIn('slug', AboutPage::slugsIn('home'))->get()->keyBy('slug');
        $hero = $home->get('home-hero');

        return view('home', compact(
            'home',
            'hero',
            'featuredUpdates',
            'counters',
            'learningSettings',
            'learningFeatures'
        ));
    }
}
