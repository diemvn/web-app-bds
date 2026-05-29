<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class SepayService
{
    public function __construct(
        protected ZnsService $zns,
    ) {}

    public function handleWebhook(array $payload): ?Payment
    {
        Log::info('SePay webhook (mock)', $payload);

        $invoiceId = $payload['invoice_id'] ?? $payload['content'] ?? null;
        $amount = (int) ($payload['transferAmount'] ?? $payload['amount'] ?? 0);
        $ref = $payload['referenceCode'] ?? $payload['id'] ?? 'MOCK-'.now()->timestamp;

        if (! $invoiceId) {
            return null;
        }

        $invoice = Invoice::find($invoiceId);
        if (! $invoice || $invoice->status === InvoiceStatus::Paid) {
            return null;
        }

        return $this->recordPayment($invoice, $amount, $ref);
    }

    public function recordPayment(Invoice $invoice, int $amount, string $ref): Payment
    {
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'method' => PaymentMethod::Sepay,
            'transaction_ref' => $ref,
            'paid_at' => now(),
        ]);

        $invoice->markPaid();

        $tenant = $invoice->contract?->tenant;
        if ($tenant?->phone) {
            $this->zns->paymentConfirmed($tenant->phone, [
                'amount' => number_format($amount).'đ',
                'ref' => $ref,
            ]);
        }

        return $payment;
    }
}
