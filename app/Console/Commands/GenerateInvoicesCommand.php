<?php

namespace App\Console\Commands;

use App\Services\InvoiceService;
use Illuminate\Console\Command;

class GenerateInvoicesCommand extends Command
{
    protected $signature = 'hosty:generate-invoices {month?}';

    protected $description = 'Tạo hóa đơn cho tất cả hợp đồng active';

    public function handle(InvoiceService $service): int
    {
        $month = $this->argument('month') ?? now()->format('Y-m');
        $count = $service->generateForMonth($month);
        $this->info("Đã tạo {$count} hóa đơn cho tháng {$month}.");

        return self::SUCCESS;
    }
}
