<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $listings = Listing::query()
            ->published()
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->get();

        $staticPages = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('map.index'), 'priority' => '0.8', 'changefreq' => 'daily'],
        ];

        return response()
            ->view('public.sitemap', compact('listings', 'staticPages'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
