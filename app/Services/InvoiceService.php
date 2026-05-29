<?php

namespace App\Services;

use App\Enums\ContractStatus;
use App\Enums\InvoiceStatus;
use App\Models\ActivityLog;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\UtilityReading;
use Carbon\Carbon;

class InvoiceService
{
    public function __construct(
        protected ZnsService $zns,
    ) {}

    public function generateForMonth(?string $billingMonth = null): int
    {
        $billingMonth ??= now()->format('Y-m');
        $count = 0;

        Contract::query()
            ->where('status', ContractStatus::Active)
            ->with(['room', 'tenant'])
            ->each(function (Contract $contract) use ($billingMonth, &$count) {
                if (Invoice::query()->where('contract_id', $contract->id)->where('billing_month', $billingMonth)->exists()) {
                    return;
                }

                $this->createForContract($contract, $billingMonth);
                $count++;
            });

        return $count;
    }

    public function createForContract(Contract $contract, string $billingMonth): Invoice
    {
        $reading = UtilityReading::query()
            ->where('room_id', $contract->room_id)
            ->where('reading_month', $billingMonth)
            ->first();

        $electric = $reading?->electric_cost ?? 0;
        $water = $reading?->water_cost ?? 0;
        $service = (int) ($contract->room?->utilities_config['service_fee'] ?? 200000);
        $roomAmount = $contract->monthly_rent;
        $total = $roomAmount + $electric + $water + $service;

        $dueDate = Carbon::createFromFormat('Y-m', $billingMonth)
            ->addMonth()
            ->day(min($contract->payment_due_day, 28));

        $invoice = Invoice::create([
            'contract_id' => $contract->id,
            'billing_month' => $billingMonth,
            'room_amount' => $roomAmount,
            'electric_amount' => $electric,
            'water_amount' => $water,
            'service_amount' => $service,
            'total_amount' => $total,
            'due_date' => $dueDate,
            'status' => InvoiceStatus::Sent,
        ]);

        ActivityLog::log('invoice', 'Tạo hóa đơn '.$billingMonth, "Phòng {$contract->room?->room_number}", $invoice);

        if ($contract->tenant) {
            Notification::create([
                'tenant_id' => $contract->tenant_id,
                'type' => 'invoice',
                'title' => 'Hóa đơn tháng '.$billingMonth,
                'body' => 'Tổng tiền: '.number_format($total).'đ. Hạn thanh toán: '.$dueDate->format('d/m/Y'),
                'link' => route('tenant.invoices.show', $invoice),
            ]);
        }

        return $invoice;
    }

    public function sendBillNotifications(): void
    {
        Invoice::query()
            ->where('status', InvoiceStatus::Sent)
            ->whereDate('created_at', today())
            ->with('contract.tenant')
            ->each(function (Invoice $invoice) {
                $phone = $invoice->contract?->tenant?->phone;
                if ($phone) {
                    $this->zns->billCreated($phone, [
                        'month' => $invoice->billing_month,
                        'amount' => number_format($invoice->total_amount).'đ',
                        'due' => $invoice->due_date->format('d/m/Y'),
                    ]);
                }
            });
    }

    public function sendReminders(int $level = 1): void
    {
        Invoice::query()
            ->whereIn('status', [InvoiceStatus::Sent, InvoiceStatus::Overdue])
            ->with('contract.tenant')
            ->each(function (Invoice $invoice) use ($level) {
                $phone = $invoice->contract?->tenant?->phone;
                if ($phone) {
                    $this->zns->billReminder($phone, [
                        'month' => $invoice->billing_month,
                        'amount' => number_format($invoice->total_amount).'đ',
                    ], $level);
                }
            });
    }

    public function markOverdue(): int
    {
        return Invoice::query()
            ->where('status', InvoiceStatus::Sent)
            ->where('due_date', '<', today())
            ->update(['status' => InvoiceStatus::Overdue]);
    }
}
