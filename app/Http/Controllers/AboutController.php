<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;

class AboutController extends Controller
{
    public function show(string $slug)
    {
        $page = AboutPage::with('items')->where('slug', $slug)->firstOrFail();

        return view('about.' . $slug, compact('page'));
    }
}
