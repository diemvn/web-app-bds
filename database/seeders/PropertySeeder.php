<?php

namespace Database\Seeders;

use App\Enums\ListingStatus;
use App\Enums\RoomStatus;
use App\Models\Building;
use App\Models\Listing;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PropertySeeder extends Seeder
{
    private function downloadImage(string $url, string $folder): string
    {
        try {
            $response = Http::timeout(10)->get($url);
            if ($response->successful()) {
                $filename = $folder . '/' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {}
        return ''; // Fallback
    }

    public function run(): void
    {
        // Ensure directories exist
        Storage::disk('public')->makeDirectory('buildings');
        Storage::disk('public')->makeDirectory('rooms');

        $buildings = [
            [
                'name' => 'Tòa nhà Pearl Plaza - Bình Thạnh',
                'address' => '561A Điện Biên Phủ',
                'district' => 'Bình Thạnh',
                'lat' => 10.7981,
                'lng' => 106.7165,
                'images' => ['https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80'],
            ],
            [
                'name' => 'Căn hộ dịch vụ Central - Quận 1',
                'address' => '12 Nguyễn Thị Minh Khai',
                'district' => 'Quận 1',
                'lat' => 10.7853,
                'lng' => 106.7021,
                'images' => ['https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&q=80'],
            ],
            [
                'name' => 'Khu trọ cao cấp Thảo Điền - Quận 2',
                'address' => '15 Quốc Hương',
                'district' => 'Quận 2',
                'lat' => 10.8030,
                'lng' => 106.7329,
                'images' => ['https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80'],
            ],
            [
                'name' => 'Tòa nhà sinh viên - Thủ Đức',
                'address' => '88 Võ Văn Ngân',
                'district' => 'Thủ Đức',
                'lat' => 10.8505,
                'lng' => 106.7717,
                'images' => ['https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80'],
            ],
        ];

        // Download building images
        foreach ($buildings as &$b) {
            $localImages = [];
            foreach ($b['images'] as $url) {
                $path = $this->downloadImage($url, 'buildings');
                if ($path) $localImages[] = $path;
            }
            $b['images'] = $localImages;
        }

        $roomImageUrls = [
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80', // bedroom
            'https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=800&q=80', // living
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80', // interior
            'https://images.unsplash.com/photo-1554995207-c18c203602cb?w=800&q=80', // bright
            'https://images.unsplash.com/photo-1536376072261-38c75010e6c9?w=800&q=80', // cozy
        ];

        // Pre-download room images
        $localRoomImages = [];
        foreach ($roomImageUrls as $url) {
            $path = $this->downloadImage($url, 'rooms');
            if ($path) $localRoomImages[] = $path;
        }

        $roomIndex = 0;
        foreach ($buildings as $bData) {
            $building = Building::create(array_merge($bData, [
                'amenities' => ['wifi', 'giữ xe', 'thang máy', 'bảo vệ 24/7', 'camera an ninh'],
            ]));

            for ($f = 1; $f <= 3; $f++) {
                for ($r = 1; $r <= 4; $r++) {
                    $roomIndex++;
                    $status = $roomIndex % 4 === 0 ? RoomStatus::Available : RoomStatus::Occupied;
                    $price = random_int(35, 85) * 100000;
                    
                    $images = $localRoomImages;
                    shuffle($images);
                    $images = array_slice($images, 0, 3); // take 3 images

                    $room = Room::create([
                        'building_id' => $building->id,
                        'floor' => $f,
                        'room_number' => sprintf('%d%02d', $f, $r),
                        'area_m2' => random_int(18, 45),
                        'base_price' => $price,
                        'status' => $status,
                        'amenities' => ['máy lạnh', 'nóng lạnh', 'tủ lạnh', 'máy giặt riêng', 'bếp', 'giường nệm'],
                        'utilities_config' => ['service_fee' => 200000],
                        'images' => $images,
                    ]);

                    if ($status === RoomStatus::Available) {
                        Listing::create([
                            'room_id' => $room->id,
                            'title' => "Phòng {$room->area_m2}m² - Full nội thất, dọn vào ngay",
                            'slug' => "phong-{$building->id}-{$room->id}-" . rand(100, 999),
                            'description' => '<p>Phòng mới setup cực đẹp, thiết kế hiện đại, nhiều ánh sáng tự nhiên. Nằm trong tòa nhà an ninh 24/7, ra vào vân tay tự do giờ giấc.</p><ul><li>Đầy đủ nội thất y hình</li><li>Máy giặt riêng từng phòng</li><li>Khu phơi đồ thoáng mát</li><li>Có chỗ đậu xe miễn phí dưới hầm</li></ul>',
                            'price' => $price,
                            'area_m2' => $room->area_m2,
                            'lat' => $building->lat + (random_int(-100, 100) / 10000),
                            'lng' => $building->lng + (random_int(-100, 100) / 10000),
                            'amenities' => $room->amenities,
                            'images' => $images,
                            'status' => ListingStatus::Published,
                            'published_at' => now()->subDays(random_int(1, 15)),
                        ]);
                    }
                }
            }
        }
    }
}
