<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\ZnsService;

class PaymentObserver
{
    public function __construct(protected ZnsService $zns) {}

    public function created(Payment $payment): void
    {
        $invoice = $payment->invoice;
        if ($invoice && ! $invoice->paid_at) {
            $invoice->markPaid();
            $phone = $invoice->contract?->tenant?->phone;
            if ($phone) {
                $this->zns->paymentConfirmed($phone, [
                    'amount' => number_format($payment->amount).'đ',
                    'ref' => $payment->transaction_ref ?? 'N/A',
                ]);
            }
        }
    }
}
