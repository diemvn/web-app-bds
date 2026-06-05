<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\SupportTicket;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $branding = SystemSetting::branding();
        
        $faqsByCategory = Faq::where('is_active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('public.support', compact('branding', 'faqsByCategory'));
    }

    public function submitTicket(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
        }

        SupportTicket::create($validated);

        return back()->with('success', 'Yêu cầu hỗ trợ của bạn đã được gửi. Chúng tôi sẽ liên hệ lại trong thời gian sớm nhất!');
    }
}
