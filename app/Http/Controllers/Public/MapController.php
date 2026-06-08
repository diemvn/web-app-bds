<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Support\SeoData;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::query()->published()->with('room.building');

        // Lọc theo bounds (tọa độ hiển thị trên màn hình)
        if ($request->filled(['swLat', 'swLng', 'neLat', 'neLng'])) {
            $query->withinBounds(
                $request->float('swLat'),
                $request->float('swLng'),
                $request->float('neLat'),
                $request->float('neLng')
            );
        }

        // Lọc theo mức giá
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->integer('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->integer('price_max'));
        }

        // Lọc theo diện tích
        if ($request->filled('area_min')) {
            $query->where('area_m2', '>=', $request->integer('area_min'));
        }
        if ($request->filled('area_max')) {
            $query->where('area_m2', '<=', $request->integer('area_max'));
        }

        // Lọc theo tiện ích (amenities)
        if ($request->filled('amenities') && is_array($request->amenities)) {
            foreach ($request->amenities as $amenity) {
                // Sử dụng json_contains với array postgres / mysql tùy driver. 
                // Tuy nhiên Laravel builder whereJsonContains() sẽ lo vụ này.
                $query->whereJsonContains('amenities', $amenity);
            }
        }

        $total = $query->count();
        
        $listings = $query->latest('published_at')->limit(50)->get();

        if ($request->wantsJson()) {
            return response()->json([
                'listings' => $listings,
                'total' => $total,
                'html' => view('public.partials.map-listings', compact('listings'))->render(),
            ]);
        }

        $seo = SeoData::forMap();

        return view('public.map', compact('listings', 'total', 'seo'));
    }
}
