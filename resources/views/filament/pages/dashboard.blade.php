<x-filament-panels::page>
    @php
        $stats = app(\App\Services\DashboardService::class)->stats();
        $user = auth()->user();
    @endphp

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Chào mừng trở lại, {{ $user?->name ?? 'Quản lý' }} 👋</h1>
        <p class="text-sm text-gray-500 mt-1">Tổng quan hoạt động hôm nay — {{ now()->translatedFormat('l, d/m/Y') }}</p>
    </div>

    <x-filament-widgets::widgets
        :widgets="$this->getVisibleWidgets()"
        :columns="$this->getColumns()"
    />
</x-filament-panels::page>
