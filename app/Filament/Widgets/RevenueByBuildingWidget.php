<?php

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\ChartWidget;

class RevenueByBuildingWidget extends ChartWidget
{
    protected ?string $heading = 'Doanh thu theo tòa nhà (tháng này)';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $buildings = app(DashboardService::class)->revenueByBuilding();

        return [
            'datasets' => [
                [
                    'label' => 'Doanh thu',
                    'data' => array_column($buildings, 'total'),
                    'backgroundColor' => ['#FF5A5F', '#FF8A80', '#FFB4A8', '#FFD4CF'],
                ],
            ],
            'labels' => array_column($buildings, 'name'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
