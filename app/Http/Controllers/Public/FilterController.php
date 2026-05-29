<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('home', array_merge(
            $request->only(['q', 'price_min', 'price_max', 'area_min']),
            ['filters' => 'open'],
        ));
    }
}
