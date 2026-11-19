<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\Counter;
use App\Models\Event;
use App\Models\LearningFeature;
use App\Models\LearningSetting;

class HomePageController extends Controller
{
    /** One card per home page section, in the order they appear on the home page. */
    public function index()
    {
        $pages = AboutPage::whereIn('slug', AboutPage::slugsIn('home'))
            ->withCount(['items', 'items as active_items_count' => fn ($q) => $q->where('is_active', true)])
            ->get()->keyBy('slug');

        $section = fn (string $slug) => [
            'label'       => AboutPage::PAGES[$slug]['label'],
            'icon'        => AboutPage::PAGES[$slug]['icon'],
            'description' => AboutPage::PAGES[$slug]['description'],
            'url'         => route('admin.about.edit', $slug),
            'meta'        => $pages->has($slug) && AboutPage::PAGES[$slug]['groups']
                ? $pages[$slug]->active_items_count . ' shown' . ($pages[$slug]->items_count > $pages[$slug]->active_items_count ? ', ' . ($pages[$slug]->items_count - $pages[$slug]->active_items_count) . ' hidden' : '')
                : 'Text and picture',
            'available'   => $pages->has($slug),
        ];

        $learning = LearningSetting::current();

        $sections = [
            $section('home-hero'),
            [
                'label'       => 'Home Counters',
                'icon'        => 'fa-chart-simple',
                'description' => 'The animated figures under the banner (students, programmes, staff and so on).',
                'url'         => route('admin.counters.index'),
                'meta'        => Counter::active()->count() . ' shown',
                'available'   => true,
            ],
            $section('home-values'),
            [
                'label'       => 'Online & Campus',
                'icon'        => 'fa-graduation-cap',
                'description' => 'The study modes list and the campus video.',
                'url'         => route('admin.learning.index'),
                'meta'        => LearningFeature::active()->count() . ' study modes' . ($learning->is_active ? '' : ' · section hidden'),
                'available'   => true,
            ],
            [
                'label'       => 'Latest Updates',
                'icon'        => 'fa-newspaper',
                'description' => 'Shows the 3 newest featured items from News & Events. Mark a news item as featured to show it here.',
                'url'         => route('admin.events.index'),
                'meta'        => Event::featured()->count() . ' featured',
                'available'   => true,
            ],
            $section('home-scholarships'),
            $section('home-campus'),
            $section('home-environment'),
            $section('home-contact'),
        ];

        return view('admin.home.index', compact('sections'));
    }
}
