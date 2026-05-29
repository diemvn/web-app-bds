<?php

namespace App\Services;

use App\Enums\ContractStatus;
use App\Enums\InvoiceStatus;
use App\Enums\RoomStatus;
use App\Models\Building;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function stats(): array
    {
        $monthStart = now()->startOfMonth();
        $revenue = Invoice::query()
            ->where('status', InvoiceStatus::Paid)
            ->where('paid_at', '>=', $monthStart)
            ->sum('total_amount');

        $totalRooms = Room::count();
        $occupied = Room::where('status', RoomStatus::Occupied)->count();
        $vacant = Room::where('status', RoomStatus::Available)->count();
        $occupancy = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100, 1) : 0;

        $expiring = Contract::query()
            ->where('status', ContractStatus::Active)
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->count();

        $debt = Invoice::query()
            ->whereIn('status', [InvoiceStatus::Sent, InvoiceStatus::Overdue])
            ->sum('total_amount');

        return [
            'revenue' => $revenue,
            'occupancy' => $occupancy,
            'vacant' => $vacant,
            'expiring' => $expiring,
            'debt' => $debt,
        ];
    }

    public function revenueByMonth(int $months = 12): array
    {
        $data = Invoice::query()
            ->select(
                DB::raw("DATE_FORMAT(paid_at, '%Y-%m') as month"),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', InvoiceStatus::Paid)
            ->where('paid_at', '>=', now()->subMonths($months)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = [];
        $values = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $labels[] = now()->subMonths($i)->format('m/Y');
            $values[] = (int) ($data[$key] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function revenueByBuilding(): array
    {
        return Building::query()
            ->withSum(['rooms as revenue' => function ($q) {
                $q->join('contracts', 'rooms.id', '=', 'contracts.room_id')
                    ->join('invoices', 'contracts.id', '=', 'invoices.contract_id')
                    ->where('invoices.status', InvoiceStatus::Paid)
                    ->where('invoices.paid_at', '>=', now()->startOfMonth());
            }], 'invoices.total_amount')
            ->get()
            ->map(fn ($b) => [
                'name' => $b->name,
                'total' => (int) Invoice::query()
                    ->whereHas('contract.room', fn ($q) => $q->where('building_id', $b->id))
                    ->where('status', InvoiceStatus::Paid)
                    ->where('paid_at', '>=', now()->startOfMonth())
                    ->sum('total_amount'),
            ])
            ->toArray();
    }

    public function overdueDebts(int $limit = 10): array
    {
        return Invoice::query()
            ->with(['contract.tenant', 'contract.room.building'])
            ->whereIn('status', [InvoiceStatus::Sent, InvoiceStatus::Overdue])
            ->orderBy('due_date')
            ->limit($limit)
            ->get()
            ->map(fn ($inv) => [
                'tenant' => $inv->contract?->tenant?->full_name,
                'room' => $inv->contract?->room?->room_number,
                'building' => $inv->contract?->room?->building?->name,
                'amount' => $inv->total_amount,
                'due_date' => $inv->due_date,
                'status' => $inv->status->label(),
                'days_overdue' => max(0, now()->diffInDays($inv->due_date, false) * -1),
            ])
            ->toArray();
    }
}
