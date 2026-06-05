<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\SupportTicket;
use Illuminate\Database\Seeder;

class SupportSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Tiền cọc sẽ được hoàn trả khi nào?',
                'answer' => '<p>Tiền cọc sẽ được hoàn trả đầy đủ vào ngày cuối cùng của hợp đồng sau khi đã cấn trừ các chi phí điện, nước, dịch vụ chưa thanh toán và chi phí sửa chữa hư hỏng tài sản (nếu có).</p>',
                'category' => 'Hợp đồng',
                'sort_order' => 1,
            ],
            [
                'question' => 'Tôi có được phép nuôi thú cưng không?',
                'answer' => '<p>Theo nội quy của hầu hết các tòa nhà thuộc HOSTY, khách thuê <strong>không được phép</strong> nuôi chó, mèo hoặc các loại thú cưng gây tiếng ồn và ảnh hưởng vệ sinh chung. Tuy nhiên, một số cơ sở chuyên biệt có thể cho phép, vui lòng liên hệ quản lý để biết chi tiết.</p>',
                'category' => 'Nội quy',
                'sort_order' => 2,
            ],
            [
                'question' => 'Đóng tiền nhà trễ hạn bị phạt như thế nào?',
                'answer' => '<p>Hạn chót thanh toán là ngày 05 hàng tháng. Nếu trễ hạn quá 5 ngày, bạn sẽ phải chịu phí trễ hạn 5% tổng hóa đơn. Trễ quá 10 ngày, hợp đồng có thể bị đơn phương chấm dứt.</p>',
                'category' => 'Thanh toán',
                'sort_order' => 3,
            ],
            [
                'question' => 'Làm sao để báo hỏng hóc đồ đạc trong phòng?',
                'answer' => '<p>Bạn có thể sử dụng chức năng <strong>Gửi Yêu Cầu Hỗ Trợ</strong> trực tiếp trên tài khoản khách thuê, hoặc nhắn tin cho Quản lý tòa nhà qua Zalo. Bộ phận kỹ thuật sẽ xử lý trong vòng 24-48 giờ làm việc.</p>',
                'category' => 'Chung',
                'sort_order' => 4,
            ],
            [
                'question' => 'Khách đến chơi có được ngủ lại qua đêm không?',
                'answer' => '<p>Bạn được phép có tối đa 2 khách ngủ lại qua đêm nhưng <strong>phải đăng ký tạm trú</strong> với ban quản lý tòa nhà và đóng thêm phí dịch vụ theo quy định (thường là 50,000 VND/người/đêm).</p>',
                'category' => 'Nội quy',
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }

        $tickets = [
            [
                'name' => 'Anh Nam',
                'phone' => '0909123456',
                'subject' => 'Máy lạnh bị rỉ nước',
                'message' => 'Máy lạnh phòng tôi tự nhiên rỉ nước xuống sàn, mong kỹ thuật qua xem giúp.',
                'status' => 'new',
            ],
            [
                'name' => 'Chị Lan',
                'phone' => '0918765432',
                'subject' => 'Hỏi về việc chuyển phòng',
                'message' => 'Tôi muốn chuyển sang phòng lớn hơn ở cùng tòa nhà có được không? Thủ tục như thế nào?',
                'status' => 'resolved',
            ],
            [
                'name' => 'Anh Tuấn',
                'phone' => '0933111222',
                'subject' => 'Bóng đèn nhà vệ sinh bị cháy',
                'message' => 'Nhờ quản lý thay dùm tôi bóng đèn trong nhà vệ sinh.',
                'status' => 'in_progress',
            ],
            [
                'name' => 'Nguyễn Văn A', // guest
                'phone' => '0901234567',
                'subject' => 'Hỏi thuê phòng ở tòa Bình Thạnh',
                'message' => 'Cho mình hỏi tòa Bình Thạnh còn phòng studio nào khoảng 4.5tr không ạ?',
                'status' => 'new',
            ],
        ];

        foreach ($tickets as $idx => $ticket) {
            // Assign user_id randomly to first 3 tickets
            if ($idx < 3) {
                $ticket['user_id'] = $idx + 2; // tenants
            }
            SupportTicket::create($ticket);
        }
    }
}
