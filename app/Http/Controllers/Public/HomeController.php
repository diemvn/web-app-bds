<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Public\Concerns\BuildsListingSearch;
use App\Support\SeoData;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use BuildsListingSearch;

    public function index(Request $request)
    {
        $listings = $this->listingSearchQuery($request)
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        if ($request->header('X-Partial-Listings') === '1' || $request->boolean('_partial')) {
            return view('public.partials.listings-feed', compact('listings'));
        }

        $seo = SeoData::forHome();

        return view('public.home', compact('listings', 'seo'));
    }
}
