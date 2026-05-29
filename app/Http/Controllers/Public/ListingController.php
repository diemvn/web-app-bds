<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Support\SeoData;

class ListingController extends Controller
{
    public function show(string $slug)
    {
        $listing = Listing::query()
            ->published()
            ->where('slug', $slug)
            ->with('room.building')
            ->firstOrFail();

        $similar = Listing::query()
            ->published()
            ->where('id', '!=', $listing->id)
            ->when($listing->room?->building?->district, fn ($q, $d) => $q->whereHas('room.building', fn ($b) => $b->where('district', $d)))
            ->limit(4)
            ->get();

        $seo = SeoData::forListing($listing);

        return view('public.listing', compact('listing', 'similar', 'seo'));
    }
}
