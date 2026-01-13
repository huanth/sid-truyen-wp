# Sid Truyện - WordPress Theme

Một giao diện WordPress tùy chỉnh được thiết kế chuyên biệt cho việc đọc truyện, sở hữu giao diện hiện đại, hỗ trợ chế độ tối (Dark Mode) và nhiều tính năng đọc nâng cao.

## 🌟 Tính Năng Nổi Bật

### 📖 Trải Nghiệm Đọc
-   **Giao diện chương hiện đại**: Thanh công cụ thông minh (Sticky Toolbar) với hiệu ứng kính (glassmorphism), thanh tiến trình đọc ở trên cùng và typography được tối ưu hóa.
-   **Bố cục toàn màn hình**: Khu vực đọc rộng rãi, căn chỉnh thẳng hàng với header, mang lại cảm giác thoáng đãng.
-   **Chế độ tối (Dark Mode)**: Hỗ trợ giao diện tối tự nhiên, bảo vệ mắt (tự động theo hệ thống hoặc chuyển đổi thủ công).
-   **Điều chỉnh chữ**: Tùy chỉnh kích thước font chữ (A+/A-) dễ dàng.

### 📥 Tải Ebook
-   **Tải trọn bộ**: Tạo file `.txt` chứa toàn bộ nội dung truyện (Thông tin + Tất cả các chương) chỉ với một cú click.
-   **Tải chương lẻ**: Cho phép tải nhanh nội dung của từng chương đang đọc.
-   **Widget cao cấp**: Nút tải xuống được thiết kế nổi bật phong cách thẻ (Card-style) với hiệu ứng gradient và animation đẹp mắt.

### 🎨 Thiết Kế & Giao Diện
-   **Tailwind CSS**: Xây dựng trên nền tảng utility-first CSS giúp giao diện nhất quán và load nhanh.
-   **Responsive**: Tương thích hoàn hảo trên mọi thiết bị: Mobile, Tablet và Desktop.
-   **Truyện Hot**: Khu vực hiển thị truyện nổi bật động trên trang chủ.

## 🛠️ Công Nghệ Sử Dụng
-   **CMS**: WordPress 6.x+
-   **Styling**: Tailwind CSS (v3.x)
-   **Icons**: Heroicons / SVG
-   **Build Tool**: NPM / Tailwind CLI

## 🚀 Hướng Dẫn Phát Triển

### Yêu cầu tiên quyết
-   Node.js & NPM
-   Môi trường WordPress Local (ví dụ: LocalWP, XAMPP, Docker)

### Cài đặt
1.  Clone repository vào thư mục `wp-content/themes/`.
2.  Cài đặt các thư viện dependencies:
    ```bash
    cd sid-truyen
    npm install
    ```
3.  Chạy môi trường phát triển (Watch mode - tự động compile khi sửa code):
    ```bash
    npm run dev
    ```
    *(Lưu ý: Đảm bảo `package.json` của bạn có script watch, thường là `npx tailwindcss -i ./src/input.css -o ./assets/css/output.css --watch`)*

4.  Build cho Production (để đưa lên host):
    ```bash
    npm run build
    ```

## 📁 Cấu Trúc Thư Mục
-   `front-page.php`: Bố cục Trang chủ (Truyện Hot, Mới cập nhật...).
-   `single-novel.php`: Trang thông tin Truyện (Danh sách chương, Tải Ebook sidebar).
-   `single-chapter.php`: Giao diện Đọc chương (Toolbar, Nội dung).
-   `functions.php`: Xử lý logic theme (SEO, Meta tags, Tạo file Ebook).
-   `assets/`: Chứa file CSS đã compile, hình ảnh và JS.

---
*Phát triển cho dự án Sid Truyện.*
