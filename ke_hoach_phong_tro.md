# 🏠 Kế Hoạch Triển Khai Hệ Thống Quản Lý & Cho Thuê Phòng Trọ

> **Stack:** Laravel 11/13 · Filament 4 · Blade/Alpine.js · Leaflet.js · Zalo ZNS · Cloudflare R2 · SePay  
> **Thời gian:** 16 tuần (4 phase)  
> **Mô hình:** Multi-building, multi-role, tự động hóa hóa đơn + ZNS

---

## 📐 Kiến Trúc Tổng Thể

```
┌─────────────────────────────────────────────────────────┐
│  PUBLIC FRONTEND (Blade + Alpine.js + Tailwind CSS)      │
│  ├── Trang tìm kiếm Map (Leaflet + OSM + Overlay VN)    │
│  ├── Trang chi tiết phòng                               │
│  └── Tenant Portal (xem HĐ, lịch sử, PDF)              │
├─────────────────────────────────────────────────────────┤
│  FILAMENT ADMIN PANEL                                    │
│  ├── Tòa nhà & Phòng                                    │
│  ├── Khách thuê & Hợp đồng                              │
│  ├── Hóa đơn & Thanh toán                               │
│  ├── Đăng tin (Listing)                                 │
│  ├── Chi phí vận hành                                   │
│  └── Dashboard doanh thu / lợi nhuận                    │
├─────────────────────────────────────────────────────────┤
│  BACKGROUND SERVICES                                     │
│  ├── Laravel Scheduler → Tạo HĐ tự động ngày 25/tháng  │
│  ├── Laravel Queue + Horizon → Gửi ZNS, SePay match     │
│  └── Supervisor → Giữ worker luôn chạy                 │
└─────────────────────────────────────────────────────────┘
```

---

## 📋 Phase 1 – Nền Tảng (Tuần 1–4)

### 1.1 Database Schema & Migrations (Tuần 1)
- Migrations đầy đủ: `buildings`, `rooms`, `tenants`, `contracts`, `invoices`, `listings`, `expenses`, `utility_readings`
- MySQL 8 Spatial Index trên cột `location` (POINT) để query map theo bounds
- Seeder: dữ liệu mẫu, roles, permissions

### 1.2 Auth + Spatie Roles & Permissions (Tuần 2)
**5 roles chính:**

| Role | Mô tả |
|---|---|
| `super_admin` | Toàn quyền hệ thống |
| `quan_ly` | Quản lý tòa nhà được gán |
| `nhan_vien` | CRUD phòng, đăng tin, ghi nhận TT |
| `ke_toan` | Xem + xuất báo cáo tài chính |
| `khach_thue` | Portal cá nhân (xem HĐ, lịch sử) |

### 1.3 Filament: Tòa Nhà & Phòng CRUD (Tuần 2–3)
- `BuildingResource`: địa chỉ, tọa độ lat/lng (map picker), ảnh R2, tiện ích JSON
- `RoomResource`: tầng, số phòng, giá, trạng thái enum (`available|occupied|maintenance`), cấu hình điện nước

### 1.4 Filament: Khách Thuê & Hợp Đồng CRUD (Tuần 3–4)
- `TenantResource`: CCCD, phone (ZNS), zalo_user_id, upload ảnh CCCD 2 mặt R2
- `ContractResource`: liên kết phòng–khách, ngày thu tiền (`payment_due_day`), sinh PDF hợp đồng

---

## 💰 Phase 2 – Tài Chính & ZNS (Tuần 5–8)

### 2.1 Nhập Chỉ Số Điện Nước Hàng Tháng (Tuần 5)
Màn hình Filament nhập chỉ số từng phòng → tự tính tiêu thụ × đơn giá.

### 2.2 Tạo Hóa Đơn Tự Động (Tuần 5–6)

**Luồng tự động:**
```
Ngày 25 hàng tháng
  └─ Scheduler tạo Invoice cho mọi contract active
      ├── Tiền phòng cơ bản
      ├── Điện: (chỉ số cuối - đầu) × đơn giá/kWh
      ├── Nước: (chỉ số cuối - đầu) × đơn giá/m3
      ├── Phí dịch vụ (cố định)
      └── Phát sinh khác (manual add)

Ngày 1 tháng sau → Gửi ZNS bill_created
Ngày 5 (chưa TT) → Gửi ZNS bill_reminder_1
Ngày 10 (chưa TT) → Gửi ZNS bill_reminder_2
```

### 2.3 Ghi Nhận Thanh Toán + SePay Webhook (Tuần 6–7)
- SePay webhook → auto-match chuyển khoản → cập nhật `invoice.status = paid`
- Ghi nhận tiền mặt thủ công qua Filament
- Kích hoạt event `PaymentRecorded` → gửi ZNS `payment_confirmed`

### 2.4 Zalo ZNS – 7 Template (Tuần 7)

| Template | Trigger | Nội dung |
|---|---|---|
| `bill_created` | Cron ngày 1 | Hóa đơn tháng X: Y VNĐ, hạn Z |
| `bill_reminder_1` | Cron ngày 5 | Nhắc lần 1 chưa thanh toán |
| `bill_reminder_2` | Cron ngày 10 | Nhắc lần 2 – escalate |
| `payment_confirmed` | Event | Đã nhận Y VNĐ, mã GD: Z |
| `contract_expiring` | Cron daily | HĐ hết hạn sau 30 ngày |
| `maintenance_update` | Event | Sự cố phòng X đang xử lý |
| `contract_signed` | Event | Chào mừng + thông tin HĐ |

> **Lưu ý:** Tái dụng codebase từ WooCommerce ZNS plugin v3. Cần fallback SMS (Esms/SpeedSMS) nếu khách không dùng Zalo.

### 2.5 Xuất PDF + Excel (Tuần 7–8)
- DomPDF: hóa đơn PDF chuyên nghiệp, hợp đồng PDF
- PhpSpreadsheet: danh sách công nợ, doanh thu tháng

### 2.6 Dashboard Tài Chính – Filament Widgets (Tuần 8)
- `StatsOverview`: tổng thu tháng, tỷ lệ lấp đầy, phòng trống, số HĐ hết hạn
- `LineChart`: doanh thu 12 tháng (có thể so kế hoạch)
- `BarChart`: thu theo tòa nhà
- `Table`: top phòng công nợ lâu nhất

---

## 🗺️ Phase 3 – Đăng Tin & Bản Đồ (Tuần 9–12)

### 3.1 Filament: Quản Lý Listing (Tuần 9)
- `ListingResource`: tiêu đề, mô tả rich text, ảnh (≤10, upload R2), tọa độ, trạng thái `draft|published|paused|rented`
- Phân quyền: nhân viên tạo → quản lý duyệt → publish

### 3.2 API GeoJSON + Spatial Query (Tuần 10)

```php
// GET /api/listings/map?sw_lat=&sw_lng=&ne_lat=&ne_lng=&price_max=&type=
public function index(Request $request)
{
    $listings = Listing::published()
        ->withinBounds($request->sw_lat, $request->sw_lng, $request->ne_lat, $request->ne_lng)
        ->limit(200)
        ->get();

    return response()->json([
        'type' => 'FeatureCollection',
        'features' => $listings->map(fn($l) => [
            'type' => 'Feature',
            'geometry' => ['type' => 'Point', 'coordinates' => [$l->lng, $l->lat]],
            'properties' => [
                'id' => $l->id, 'slug' => $l->slug,
                'title' => $l->title,
                'price_display' => number_format($l->price) . ' VNĐ/tháng',
                'area' => $l->area_m2 . ' m²',
                'image' => $l->thumbnail_url,
            ]
        ])
    ]);
}
```

### 3.3 Frontend Map – Leaflet + Clustering (Tuần 10–11)

**Nguyên tắc bản đồ Việt Nam:**
- Tile nền: OpenStreetMap (miễn phí, ổn định)
- Hoàng Sa / Trường Sa: **hardcode trong code** → không phụ thuộc tile provider
- Cluster: `leaflet.markercluster` → UX giống Zillow

```javascript
// Overlay Hoàng Sa / Trường Sa – LUÔN đúng, không bao giờ thay đổi
L.polygon([[17.1,111.1],[17.1,112.9],[15.7,112.9],[15.7,111.1]], {
    color: '#c0392b', fillOpacity: 0.08, dashArray: '5,5'
}).bindPopup('<b>🇻🇳 Quần đảo Hoàng Sa</b><br>Huyện Hoàng Sa, TP. Đà Nẵng').addTo(map);

L.polygon([[12.0,113.5],[12.0,117.5],[7.0,117.5],[7.0,113.5]], {
    color: '#c0392b', fillOpacity: 0.08, dashArray: '5,5'
}).bindPopup('<b>🇻🇳 Quần đảo Trường Sa</b><br>Huyện Trường Sa, Tỉnh Khánh Hòa').addTo(map);
```

> **Lý do không dùng Google Maps:** Google có lịch sử thay đổi nhãn Hoàng Sa/Trường Sa nhiều lần (2015, 2020, 2023) ngoài tầm kiểm soát.

### 3.4 Trang Chi Tiết Phòng (Tuần 11–12)
- Gallery ảnh slideshow, thông tin đầy đủ, tiện ích
- Mini-map Leaflet vị trí tòa nhà
- Form liên hệ / đặt lịch xem phòng
- Phòng tương tự cùng khu vực

### 3.5 Tìm Kiếm & Filter Nâng Cao (Tuần 12)
- Filter: loại phòng, giá min–max, diện tích, quận/huyện, tiện ích
- URL-based filter (shareable link)
- Livewire cho filter realtime không reload trang

---

## 🎯 Phase 4 – Portal & Hoàn Thiện (Tuần 13–16)

### 4.1 Tenant Portal (Tuần 13)
- Login bằng số điện thoại (OTP hoặc password đơn giản)
- Xem hợp đồng, lịch sử hóa đơn, lịch sử thanh toán
- Tải PDF hóa đơn từng tháng
- Xem thông báo cá nhân

### 4.2 In-app Notification Center (Tuần 14)
- Chuông thông báo trên Filament
- Trạng thái đọc/chưa đọc
- Liên kết đến record liên quan (hóa đơn, hợp đồng, sự cố)

### 4.3 Báo Cáo Lợi Nhuận & Khấu Hao (Tuần 14–15)
- Module `expenses`: điện chung, nước chung, nhân công, sửa chữa
- **Công thức:** `Lợi nhuận = Tổng thu – Chi phí vận hành – Khấu hao`
- Báo cáo theo tháng / theo tòa nhà / toàn hệ thống

### 4.4 UAT & Bug Fixing (Tuần 15–16)
- Test end-to-end tất cả luồng: HĐ → HĐ đơn → TT → ZNS
- Performance test spatial query với dữ liệu thật
- Security audit: SQL injection, file upload, auth bypass

### 4.5 Deploy Production + Training (Tuần 16)
- aaPanel + Nginx + PHP 8.4 + Redis
- Supervisor config cho Queue worker
- Cloudflare CDN cho static assets + R2
- Training nhân viên 1–2 buổi

---

## 🛠️ Tech Stack Chi Tiết

| Layer | Công nghệ | Chi phí |
|---|---|---|
| Backend | Laravel 11 | Free |
| Admin | Filament 4 | Free |
| Frontend | Blade + Alpine.js + Tailwind | Free |
| Map render | Leaflet.js 1.9 | Free |
| Tile map | OpenStreetMap | Free |
| Clustering | leaflet.markercluster | Free |
| Database | MySQL 8 + Spatial Index | Hosting |
| Cache/Queue | Redis + Laravel Horizon | ~$5–10/tháng |
| Storage | Cloudflare R2 | Free ≤10GB |
| ZNS | Zalo ZNS API | Theo template |
| Payment | SePay Webhook | ~0.1% GD |
| PDF | Laravel DomPDF | Free |
| Excel | PhpSpreadsheet | Free |
| Web server | Nginx + PHP 8.4 (aaPanel) | Hosting |
| SMS fallback | Esms / SpeedSMS | Theo SMS |

---

## 🗄️ Database Schema Tóm Tắt

```
buildings           → id, name, address, lat, lng (POINT), amenities(JSON)
  └── rooms         → building_id, floor, room_number, base_price, status, utilities_config(JSON)
       └── contracts → room_id, tenant_id, start_date, end_date, payment_due_day, status
            └── invoices → contract_id, billing_month, electric/water readings, total_amount, status
                 └── payments → invoice_id, amount, method, transaction_ref, paid_at

tenants             → id, full_name, phone, cccd, zalo_user_id, documents(JSON)
listings            → room_id, title, slug, lat, lng, images(JSON), status, published_at
expenses            → building_id, category, amount, invoice_date, receipt_image
utility_readings    → room_id, reading_month, electric_start, electric_end, water_start, water_end
```

---

## ⚠️ Lưu Ý Quan Trọng

1. **Bản đồ chủ quyền:** Hoàng Sa / Trường Sa phải hardcode trong JS, không bao giờ phụ thuộc tile provider bên ngoài.

2. **ZNS fallback:** Luôn có SMS backup (Esms/SpeedSMS) khi khách không dùng Zalo.

3. **Chỉ số điện nước:** Đây là điểm đau nhất của PM phòng trọ VN — cần UX tốt, hỗ trợ import bulk từ Excel.

4. **Multi-building từ đầu:** Thiết kế theo hướng multi-landlord để dễ SaaS hóa sau này.

5. **Spatial Index MySQL:** Bắt buộc tạo từ đầu, không thể thêm sau khi đã có dữ liệu lớn.

6. **Queue worker:** Supervisor phải config `autorestart=true`, không để ZNS job thất bại âm thầm.

---

*Tài liệu này đi kèm với file Excel chi tiết: `ke_hoach_phong_tro.xlsx`*
