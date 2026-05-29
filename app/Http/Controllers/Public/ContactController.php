<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Listing;
use App\Support\SeoData;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create(string $slug)
    {
        $listing = Listing::query()->published()->where('slug', $slug)->with('room.building')->firstOrFail();

        $seo = SeoData::forContact($listing);

        return view('public.contact', compact('listing', 'seo'));
    }

    public function store(Request $request, string $slug)
    {
        $listing = Listing::query()->published()->where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20'],
            'move_in_date' => ['nullable', 'date'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        ActivityLog::log(
            'inquiry',
            'Yêu cầu xem phòng: '.$listing->title,
            "{$data['name']} ({$data['phone']})",
            $listing,
            $data,
        );

        return redirect()
            ->route('listing.show', $listing->slug)
            ->with('success', 'Đã gửi yêu cầu. Chủ trọ sẽ liên hệ sớm!');
    }
}
