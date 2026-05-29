# Làm UI khớp 100% với Stitch

## Vì sao giao diện hiện tại chưa giống mẫu?

Code hiện tại được **dựng lại bằng Tailwind + Blade** (bắt chước layout), **không** dùng file HTML/CSS export từ Stitch.

→ Giống **~70–80%** về bố cục, **không** pixel-perfect (font, spacing, màu chính xác từng px).

---

## 3 cách để **chính xác như Stitch**

### Cách A — Stitch MCP (khuyến nghị)

1. Lấy API key: [stitch.withgoogle.com](https://stitch.withgoogle.com) → Avatar → **Stitch settings** → **API key** → Create.

2. Cursor → **Settings → MCP → Add new global server**:

```json
{
  "mcpServers": {
    "stitch": {
      "url": "https://stitch.googleapis.com/mcp",
      "headers": {
        "X-Goog-Api-Key": "DÁN_API_KEY_VÀO_ĐÂY"
      }
    }
  }
}
```

Hoặc copy `.cursor/mcp.json.example` → `.cursor/mcp.json` và điền key.

3. **Restart Cursor** (quan trọng).

4. Chat mới, gõ:

```
Dùng Stitch MCP, project ID 10957452606187217579.
List screens → get_screen_html từng màn → chuyển sang Blade Laravel,
giữ nguyên class/CSS, chỉ thay chỗ tĩnh bằng @foreach / {{ $listing->title }}.
```

Agent cần thấy server `stitch` **Connected** (xanh).

---

### Cách B — Export HTML thủ công (không cần MCP)

Trên từng màn hình trong Stitch:

1. Mở màn (Trang chủ, Chi tiết, Bộ lọc, …)
2. **Export / Copy code** (HTML) nếu Stitch hiển thị
3. Lưu vào:

```
design/stitch/html/trang-chu.html
design/stitch/html/chi-tiet-phong.html
design/stitch/html/bo-loc.html
design/stitch/html/lien-he.html
design/stitch/html/ban-do-desktop.html
design/stitch/screenshots/   (ảnh PNG từng màn, backup)
```

4. Chat:

```
Implement Blade từ design/stitch/html/, giữ nguyên markup Stitch, nối dữ liệu Listing.
```

---

### Cách C — DESIGN.md + ảnh từ Stitch

1. Trong Stitch: export **Design DNA** / `DESIGN.md` (nếu có)
2. Chụp PNG từng màn 390px (mobile) + desktop
3. Đặt vào `design/stitch/screenshots/`
4. Agent căn spacing/màu theo file + ảnh (khoảng **90%** khớp, vẫn không bằng HTML gốc)

---

## Map màn hình → Laravel

| Màn Stitch | Route Laravel |
|------------|---------------|
| Trang chủ | `/` |
| Chi tiết phòng | `/phong/{slug}` |
| Bộ lọc | `/bo-loc` |
| Liên hệ | `/phong/{slug}/lien-he` |
| Bản đồ Desktop | `/tim-phong` |

Dữ liệu động: `Listing`, `Building`, `Room` (đã có sẵn).

---

## Kiểm tra MCP đã hoạt động chưa

Trong chat Cursor:

```
List my Stitch projects
```

- Trả về project → MCP OK → làm Cách A
- Lỗi / không có tool → `.cursor/mcp.json` trống hoặc chưa restart Cursor

**Hiện tại:** file `.cursor/mcp.json` trong repo đang `"mcpServers": {}` → MCP **chưa cấu hình**.
