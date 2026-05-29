<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\SepayService;
use Illuminate\Http\Request;

class SepayWebhookController extends Controller
{
    public function __invoke(Request $request, SepayService $sepay)
    {
        $payment = $sepay->handleWebhook($request->all());

        return response()->json([
            'success' => $payment !== null,
            'payment_id' => $payment?->id,
        ]);
    }
}
