<?php

namespace Database\Seeders;

use App\Enums\ContractStatus;
use App\Enums\InvoiceStatus;
use App\Enums\RoomStatus;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UtilityReading;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        $tenantData = [
            ['full_name' => 'Nguyễn Văn Nam', 'phone' => '0909123456'],
            ['full_name' => 'Trần Thị Lan', 'phone' => '0918765432'],
            ['full_name' => 'Lê Minh Tuấn', 'phone' => '0933111222'],
            ['full_name' => 'Phạm Thu Hằng', 'phone' => '0988777666'],
            ['full_name' => 'Hoàng Ngọc Sơn', 'phone' => '0912333444'],
            ['full_name' => 'Bùi Kim Liên', 'phone' => '0977888999'],
        ];

        // Ensure we only process if we have occupied rooms
        $occupiedRooms = Room::where('status', RoomStatus::Occupied)->take(count($tenantData))->get();
        if ($occupiedRooms->isEmpty()) {
            return;
        }

        foreach ($tenantData as $i => $tData) {
            $tenant = Tenant::create($tData);
            // Default to first room if we run out of unique occupied rooms (though we shouldn't based on PropertySeeder count)
            $room = $occupiedRooms[$i] ?? $occupiedRooms->first();

            $startDate = now()->subMonths(random_int(3, 10));
            $contract = Contract::create([
                'room_id' => $room->id,
                'tenant_id' => $tenant->id,
                'start_date' => clone $startDate,
                'end_date' => (clone $startDate)->addMonths(12),
                'payment_due_day' => 5,
                'deposit_amount' => $room->base_price * 1.5,
                'monthly_rent' => $room->base_price,
                'status' => ContractStatus::Active,
            ]);

            User::firstOrCreate(
                ['phone' => $tenant->phone],
                [
                    'name' => $tenant->full_name,
                    'email' => "tenant{$i}@hosty.vn",
                    'password' => Hash::make('password'),
                    'tenant_id' => $tenant->id,
                ]
            )->syncRoles(['khach_thue']);

            $electricReading = random_int(1000, 3000);
            $waterReading = random_int(50, 150);

            // Generate 3 months of history
            for ($m = 2; $m >= 0; $m--) {
                $month = now()->subMonths($m)->format('Y-m');
                
                $prevElectric = $electricReading;
                $prevWater = $waterReading;
                
                $electricReading += random_int(150, 250); // usage per month
                $waterReading += random_int(10, 20); // usage per month
                
                UtilityReading::create([
                    'room_id' => $room->id,
                    'reading_month' => $month,
                    'electric_start' => $prevElectric,
                    'electric_end' => $electricReading,
                    'water_start' => $prevWater,
                    'water_end' => $waterReading,
                ]);

                $electricCost = ($electricReading - $prevElectric) * 3500;
                $waterCost = ($waterReading - $prevWater) * 20000;
                $serviceFee = 200000;

                $total = $contract->monthly_rent + $electricCost + $waterCost + $serviceFee;
                
                // M=0 is current month (Sent), M>0 is past months (Paid)
                $isPaid = $m > 0;
                
                Invoice::create([
                    'contract_id' => $contract->id,
                    'billing_month' => $month,
                    'room_amount' => $contract->monthly_rent,
                    'electric_amount' => $electricCost,
                    'water_amount' => $waterCost,
                    'service_amount' => $serviceFee,
                    'total_amount' => $total,
                    'due_date' => now()->subMonths($m)->day(min($contract->payment_due_day, 28)),
                    'status' => $isPaid ? InvoiceStatus::Paid : InvoiceStatus::Sent,
                    'paid_at' => $isPaid ? now()->subMonths($m)->day(random_int(1, 5)) : null,
                ]);
            }
        }
    }
}
