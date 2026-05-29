<?php

namespace Database\Seeders;

use App\Enums\ContractStatus;
use App\Enums\InvoiceStatus;
use App\Enums\ListingStatus;
use App\Enums\RoomStatus;
use App\Models\ActivityLog;
use App\Models\Building;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Listing;
use App\Models\Room;
use App\Models\SystemSetting;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UtilityReading;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['super_admin', 'quan_ly', 'nhan_vien', 'ke_toan', 'khach_thue'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        SystemSetting::set('app_name', 'HOSTY');
        SystemSetting::set('primary_color', '#2563EB');
        SystemSetting::set('tagline', 'Quản lý phòng trọ thông minh');

        $admin = User::firstOrCreate(
            ['email' => 'admin@hosty.local'],
            ['name' => 'Quản lý A', 'password' => Hash::make('password'), 'phone' => '0901000000']
        );
        $admin->syncRoles(['super_admin']);

        $buildings = [
            ['name' => 'Tòa nhà A - Bình Thạnh', 'address' => '123 Xô Viết Nghệ Tĩnh', 'district' => 'Bình Thạnh', 'lat' => 10.8014, 'lng' => 106.7120],
            ['name' => 'Tòa nhà B - Quận 1', 'address' => '45 Nguyễn Huệ', 'district' => 'Quận 1', 'lat' => 10.7743, 'lng' => 106.7018],
            ['name' => 'Tòa nhà C - Thủ Đức', 'address' => '88 Võ Văn Ngân', 'district' => 'Thủ Đức', 'lat' => 10.8505, 'lng' => 106.7717],
        ];

        $roomIndex = 0;
        foreach ($buildings as $bData) {
            $building = Building::create(array_merge($bData, [
                'amenities' => ['wifi', 'giữ xe', 'thang máy'],
                'images' => [],
            ]));

            for ($f = 1; $f <= 3; $f++) {
                for ($r = 1; $r <= 4; $r++) {
                    $roomIndex++;
                    $status = $roomIndex % 5 === 0 ? RoomStatus::Available : RoomStatus::Occupied;
                    $price = random_int(35, 65) * 100000;

                    $room = Room::create([
                        'building_id' => $building->id,
                        'floor' => $f,
                        'room_number' => sprintf('%d%02d', $f, $r),
                        'area_m2' => random_int(18, 35),
                        'base_price' => $price,
                        'status' => $status,
                        'amenities' => ['máy lạnh', 'nóng lạnh', 'tủ lạnh'],
                        'utilities_config' => ['service_fee' => 200000],
                        'images' => [],
                    ]);

                    if ($status === RoomStatus::Available) {
                        Listing::create([
                            'room_id' => $room->id,
                            'title' => "Phòng {$room->area_m2}m² - Full nội thất",
                            'slug' => "phong-{$building->id}-{$room->id}",
                            'description' => '<p>Phòng đẹp, thoáng mát, full nội thất.</p>',
                            'price' => $price,
                            'area_m2' => $room->area_m2,
                            'lat' => $building->lat + (random_int(-50, 50) / 10000),
                            'lng' => $building->lng + (random_int(-50, 50) / 10000),
                            'amenities' => $room->amenities,
                            'images' => [],
                            'status' => ListingStatus::Published,
                            'published_at' => now()->subDays(random_int(1, 30)),
                        ]);
                    }
                }
            }
        }

        $tenantData = [
            ['full_name' => 'Anh Nam', 'phone' => '0909123456'],
            ['full_name' => 'Chị Lan', 'phone' => '0918765432'],
            ['full_name' => 'Anh Tuấn', 'phone' => '0933111222'],
        ];

        $occupiedRooms = Room::where('status', RoomStatus::Occupied)->take(3)->get();

        foreach ($tenantData as $i => $tData) {
            $tenant = Tenant::create($tData);
            $room = $occupiedRooms[$i] ?? $occupiedRooms->first();

            $contract = Contract::create([
                'room_id' => $room->id,
                'tenant_id' => $tenant->id,
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(18),
                'payment_due_day' => 5,
                'deposit_amount' => $room->base_price * 2,
                'monthly_rent' => $room->base_price,
                'status' => ContractStatus::Active,
            ]);

            User::firstOrCreate(
                ['phone' => $tenant->phone],
                [
                    'name' => $tenant->full_name,
                    'email' => "tenant{$i}@hosty.local",
                    'password' => Hash::make('password'),
                    'tenant_id' => $tenant->id,
                ]
            )->syncRoles(['khach_thue']);

            UtilityReading::create([
                'room_id' => $room->id,
                'reading_month' => now()->format('Y-m'),
                'electric_start' => 1000,
                'electric_end' => 1180,
                'water_start' => 50,
                'water_end' => 58,
            ]);

            foreach (range(2, 0) as $i) {
                $month = now()->subMonths($i)->format('Y-m');
                $total = $contract->monthly_rent + 630000 + 160000 + 200000;
                Invoice::create([
                    'contract_id' => $contract->id,
                    'billing_month' => $month,
                    'room_amount' => $contract->monthly_rent,
                    'electric_amount' => 630000,
                    'water_amount' => 160000,
                    'service_amount' => 200000,
                    'total_amount' => $total,
                    'due_date' => now()->subMonths($i)->day(min($contract->payment_due_day, 28)),
                    'status' => $i === 0 ? InvoiceStatus::Sent : InvoiceStatus::Paid,
                    'paid_at' => $i === 0 ? null : now()->subMonths($i),
                ]);
            }
        }

        ActivityLog::log('system', 'Khởi tạo dữ liệu mẫu', 'Seeder chạy thành công');
    }
}
