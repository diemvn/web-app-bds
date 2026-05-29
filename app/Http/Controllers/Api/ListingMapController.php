<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingMapController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::query()
            ->published()
            ->with('room.building')
            ->withinBounds(
                $request->float('sw_lat'),
                $request->float('sw_lng'),
                $request->float('ne_lat'),
                $request->float('ne_lng'),
            );

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (int) $request->price_max);
        }

        if ($request->filled('area_min')) {
            $query->where('area_m2', '>=', (float) $request->area_min);
        }

        $listings = $query->limit(200)->get();

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $listings->map(fn (Listing $l) => [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float) $l->lng, (float) $l->lat],
                ],
                'properties' => [
                    'id' => $l->id,
                    'slug' => $l->slug,
                    'title' => $l->title,
                    'price' => $l->price,
                    'price_display' => number_format($l->price).'đ/tháng',
                    'area' => ($l->area_m2 ?? $l->room?->area_m2).' m²',
                    'image' => $l->thumbnail_url,
                    'district' => $l->room?->building?->district,
                ],
            ]),
            'list' => $listings->map(fn (Listing $l) => [
                'id' => $l->id,
                'slug' => $l->slug,
                'title' => $l->title,
                'price' => $l->price,
                'price_display' => number_format($l->price).'đ/tháng',
                'area' => $l->area_m2 ?? $l->room?->area_m2,
                'image' => $l->thumbnail_url,
                'lat' => $l->lat,
                'lng' => $l->lng,
                'location' => ($l->room?->building?->district ?? '').', '.($l->room?->building?->city ?? 'TP.HCM'),
                'amenities' => $l->amenities ?? [],
            ]),
        ]);
    }
}
