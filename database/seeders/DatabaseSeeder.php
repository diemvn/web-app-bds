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
            ['email' => 'admin@hosty.vn'],
            ['name' => 'Quản lý A', 'password' => Hash::make('password'), 'phone' => '0901000000']
        );
        $admin->syncRoles(['super_admin']);

        // Call new seeders
        $this->call([
            PropertySeeder::class,
            FinanceSeeder::class,
            ContentSeeder::class,
            SupportSeeder::class,
        ]);

        ActivityLog::log('system', 'Khởi tạo dữ liệu mẫu', 'Seeder chạy thành công');
    }
}
