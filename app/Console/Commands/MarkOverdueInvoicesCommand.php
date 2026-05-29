<?php

namespace App\Console\Commands;

use App\Services\InvoiceService;
use Illuminate\Console\Command;

class MarkOverdueInvoicesCommand extends Command
{
    protected $signature = 'hosty:mark-overdue';

    protected $description = 'Đánh dấu hóa đơn quá hạn';

    public function handle(InvoiceService $service): int
    {
        $count = $service->markOverdue();
        $this->info("Đã cập nhật {$count} hóa đơn quá hạn.");

        return self::SUCCESS;
    }
}
