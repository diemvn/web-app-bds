<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Support\SeoData;

class MapController extends Controller
{
    public function index()
    {
        $listings = Listing::query()
            ->published()
            ->with('room.building')
            ->latest('published_at')
            ->limit(50)
            ->get();

        $total = Listing::query()->published()->count();

        $seo = SeoData::forMap();

        return view('public.map', compact('listings', 'total', 'seo'));
    }
}
