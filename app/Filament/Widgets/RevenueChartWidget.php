<?php

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'Doanh thu 12 tháng';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $chart = app(DashboardService::class)->revenueByMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Doanh thu',
                    'data' => $chart['values'],
                    'fill' => true,
                    'backgroundColor' => 'rgba(255, 90, 95, 0.2)',
                    'borderColor' => '#FF5A5F',
                ],
            ],
            'labels' => $chart['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'y' => ['beginAtZero' => true, 'grid' => ['color' => 'rgba(0,0,0,0.05)']],
                'x' => ['grid' => ['display' => false]],
            ],
        ];
    }
}
