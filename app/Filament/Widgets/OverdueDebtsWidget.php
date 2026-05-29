<?php

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\Widget;

class OverdueDebtsWidget extends Widget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.overdue-debts';

    public function getDebts(): array
    {
        return app(DashboardService::class)->overdueDebts();
    }
}
