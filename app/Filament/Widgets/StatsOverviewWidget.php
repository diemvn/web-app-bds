<?php

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = app(DashboardService::class)->stats();

        return [
            Stat::make('Doanh thu tháng', number_format($stats['revenue']).'đ')
                ->description('↑ 12.5% so với tháng trước')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 12, 9, 14, 15, 18, 20]),
            Stat::make('Tỷ lệ lấp đầy', $stats['occupancy'].'%')
                ->description('↑ 4% so với tháng trước')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Phòng trống', (string) $stats['vacant'])
                ->description('Sẵn sàng cho thuê'),
            Stat::make('HĐ sắp hết hạn', (string) $stats['expiring'])
                ->description('Trong 30 ngày tới')
                ->color('warning'),
        ];
    }
}
