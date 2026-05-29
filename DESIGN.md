# HOSTY — Design System (Option 1 · Airbnb)

> Nguồn: mockup `UI.png` · Màu có thể ghi đè qua Admin → Cài đặt

## Màu sắc

| Token | Giá trị | Dùng cho |
|-------|---------|----------|
| `--color-primary` | `#FF5A5F` | CTA, giá, marker map, active nav |
| `--color-primary-hover` | `#E84E52` | Hover button |
| `--color-primary-soft` | `#FFF0F0` | Nền card nhẹ |
| `--color-success` | `#008A05` | Đã thanh toán |
| `--color-warning` | `#E6A700` | Sắp hết hạn |
| `--color-danger` | `#C13515` | Quá hạn |
| `--color-surface` | `#FFFFFF` | Card, header |
| `--color-bg` | `#F7F7F7` | Nền trang |

## Typography

- Font: **DM Sans** (public) / Instrument Sans (Filament admin)
- Tiêu đề: 600–700, tracking tight
- Body: 400–500, 14–16px

## Bo góc & đổ bóng

- Card: `rounded-2xl` (16px), `shadow-sm` → `shadow-md` on hover
- Button pill: `rounded-full`
- Input: `rounded-xl` hoặc `rounded-full` (filter bar)

## Layout

- **Admin**: sidebar trắng, content `#F7F7F7`, stats 4 cột, chart 2 cột, bảng full width
- **Map**: split 384px list + map; card ảnh lớn 4:3
- **Tenant PWA**: max-width 448px, bottom nav 5 mục, CTA full width coral
