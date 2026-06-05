<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
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
        Storage::disk('public')->makeDirectory('articles');

        $categories = [
            'Kinh nghiệm thuê trọ',
            'Tin tức thị trường',
            'Thông báo',
        ];

        $categoryModels = [];
        foreach ($categories as $idx => $name) {
            $categoryModels[] = ArticleCategory::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Chuyên mục chia sẻ về {$name}",
                'sort_order' => $idx,
                'is_active' => true,
            ]);
        }

        $articles = [
            [
                'title' => '5 Kinh nghiệm xương máu khi đi tìm phòng trọ sinh viên',
                'category_id' => $categoryModels[0]->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80',
                'excerpt' => 'Tìm phòng trọ luôn là vấn đề nan giải của sinh viên mới lên thành phố. Hãy lưu ngay 5 kinh nghiệm sau đây để tránh mất tiền oan.',
                'content' => '<h2>1. Không chuyển tiền cọc khi chưa xem phòng trực tiếp</h2><p>Điều đầu tiên và quan trọng nhất là bạn tuyệt đối không được chuyển tiền cọc khi chưa đến xem phòng trực tiếp. Nhiều đối tượng lợi dụng tâm lý cần phòng gấp để lừa tiền cọc...</p><h2>2. Kiểm tra kỹ hợp đồng thuê nhà</h2><p>Hợp đồng là văn bản pháp lý quan trọng nhất. Hãy đọc kỹ từng điều khoản về giá thuê, tiền điện, nước, các khoản phí dịch vụ, và điều kiện bồi thường nếu đơn phương chấm dứt hợp đồng.</p>',
            ],
            [
                'title' => 'Giá thuê căn hộ dịch vụ tại khu vực trung tâm TP.HCM tiếp tục tăng',
                'category_id' => $categoryModels[1]->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1449844908441-8829872d2607?w=800&q=80',
                'excerpt' => 'Trong quý 3/2026, giá thuê căn hộ dịch vụ tại khu vực Quận 1, Quận 3 và Bình Thạnh ghi nhận mức tăng từ 5-10% do nhu cầu quay trở lại thành phố làm việc tăng cao.',
                'content' => '<p>Theo báo cáo mới nhất, thị trường căn hộ dịch vụ và phòng trọ cao cấp tại trung tâm TP.HCM đang trải qua đợt tăng giá đáng kể. Sự khan hiếm nguồn cung mới kết hợp với nhu cầu tăng mạnh đã đẩy giá thuê lên mức cao kỷ lục trong 2 năm qua.</p><p>Tại khu vực Quận Bình Thạnh, các phòng trọ full nội thất đang có mức giá trung bình từ 4.5 đến 6.5 triệu đồng/tháng.</p>',
            ],
            [
                'title' => 'Mẹo trang trí phòng trọ nhỏ hẹp trở nên rộng rãi và tiện nghi',
                'category_id' => $categoryModels[0]->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800&q=80',
                'excerpt' => 'Bạn đang đau đầu vì căn phòng trọ quá nhỏ? Đừng lo, những mẹo bài trí thông minh sau đây sẽ giúp không gian sống của bạn thêm phần lý tưởng.',
                'content' => '<h2>Sử dụng nội thất đa năng</h2><p>Giường ngủ kết hợp ngăn kéo chứa đồ, bàn học gấp gọn là những lựa chọn hoàn hảo cho không gian hẹp.</p><h2>Sử dụng gương để tạo cảm giác không gian rộng hơn</h2><p>Một chiếc gương lớn đặt ở góc phòng sẽ phản chiếu ánh sáng và giúp căn phòng có vẻ rộng rãi gấp đôi.</p>',
            ],
            [
                'title' => 'Quy định mới về phòng cháy chữa cháy tại các chung cư mini',
                'category_id' => $categoryModels[2]->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1542361345-89e58247f2d5?w=800&q=80',
                'excerpt' => 'Ban quản lý HOSTY xin thông báo về các quy định PCCC mới nhất được áp dụng cho toàn bộ hệ thống tòa nhà bắt đầu từ tháng này.',
                'content' => '<h2>Yêu cầu bắt buộc</h2><p>Tất cả các phòng phải được trang bị bình chữa cháy mini. Khách thuê tuyệt đối không sạc xe đạp điện, xe máy điện qua đêm dưới tầng hầm.</p>',
            ],
            [
                'title' => 'Lý do nên chọn ở Căn hộ dịch vụ thay vì Phòng trọ truyền thống',
                'category_id' => $categoryModels[0]->id,
                'thumbnail' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&q=80',
                'excerpt' => 'Sự khác biệt giữa căn hộ dịch vụ và phòng trọ truyền thống là gì? Cùng HOSTY phân tích ưu nhược điểm để đưa ra lựa chọn phù hợp nhất với tài chính của bạn.',
                'content' => '<p>Căn hộ dịch vụ cung cấp sự riêng tư, an ninh tốt hơn và đi kèm nhiều tiện ích sẵn có như vệ sinh định kỳ, máy giặt riêng, bếp rộng rãi. Tuy giá cao hơn nhưng lại mang lại sự thoải mái tuyệt đối sau một ngày làm việc căng thẳng.</p>',
            ],
        ];

        foreach ($articles as &$art) {
            $art['thumbnail'] = $this->downloadImage($art['thumbnail'], 'articles');
        }
        unset($art);

        foreach ($articles as $articleData) {
            Article::create([
                'title' => $articleData['title'],
                'slug' => Str::slug($articleData['title']),
                'article_category_id' => $articleData['category_id'],
                'author_id' => 1, // Admin
                'thumbnail' => $articleData['thumbnail'],
                'excerpt' => $articleData['excerpt'],
                'content' => $articleData['content'],
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 14)),
                'views_count' => rand(100, 5000),
                'reading_time' => rand(2, 6),
                'seo_title' => $articleData['title'],
                'seo_description' => $articleData['excerpt'],
            ]);
        }
    }
}
