# TASTE-SKILL BRIEF

## Project: HOSTY
**Type:** Consumer rental-listing PWA  
**Audience:** Vietnamese renters (18-35)  
**Vibe:** Premium-consumer, mobile-first, clean, trustworthy  
**Constraints:** 
- Stack: Laravel Blade + Alpine.js + Tailwind v4
- Colors: Material Design 3 tokens (Primary: `#003fb1`, Secondary: `#fd761a`)
- Icons: Material Symbols
- Language: Vietnamese

## Design Read
> **"Reading this as:** Consumer rental-listing PWA for Vietnamese renters, with a premium-consumer / mobile-first language, leaning toward **Material Design 3 tokens + Tailwind v4 utilities + Alpine.js reactivity**."

## Dial Settings
- `DESIGN_VARIANCE`: **7** (Premium consumer, tránh generic nhưng không quá experimental)
- `MOTION_INTENSITY`: **6** (Mobile-native feel, subtle card/page transitions)
- `VISUAL_DENSITY`: **5** (Listing data cần hiển thị nhiều nhưng vẫn breathable)

## Context Overrides
- **Typography:** Mặc định sử dụng `Inter` (vì phù hợp tiếng Việt và app interface), có thể dùng `DM Sans` cho display headings để tạo điểm nhấn premium.
- **Color:** Không sử dụng các bảng màu beige+brass/purple mặc định của AI, giữ đúng brand color (`#003fb1` và `#fd761a`).
- **Motion:** CSS transitions kết hợp `x-transition` của Alpine.js thay vì Framer Motion. Dùng `IntersectionObserver` qua class `stitch-section` để scroll reveal.
- **Layout:** Tuân thủ chặt chẽ "Section-Layout-Repetition Ban" (không lặp lại layout cùng trang), "Anti-center bias" cho Hero, "Eyebrow max 1/3 sections", "Button contrast WCAG AA".
