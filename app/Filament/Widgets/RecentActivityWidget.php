<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.recent-activity';

    public function getActivities()
    {
        return ActivityLog::query()->latest()->limit(8)->get();
    }
}
