<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $branding = SystemSetting::branding();
        
        $listings = collect();
        
        if (Auth::check()) {
            $listings = Auth::user()->favoriteListings()->with(['room.building', 'amenities'])->paginate(12);
        } else if ($request->has('ids')) {
            $ids = explode(',', $request->ids);
            if (count($ids) > 0) {
                $listings = Listing::whereIn('id', $ids)->with(['room.building', 'amenities'])->paginate(12);
            }
        }

        return view('public.favorites', compact('branding', 'listings'));
    }

    public function toggle(Request $request, Listing $listing)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'guest']);
        }

        $user = Auth::user();
        $favorite = $user->favorites()->where('listing_id', $listing->id)->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
        } else {
            $user->favorites()->create(['listing_id' => $listing->id]);
            $status = 'added';
        }

        return response()->json([
            'status' => $status,
        ]);
    }
}
