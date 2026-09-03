# HƯỚNG DẪN CÀI ĐẶT VÀ CHẠY DỰ ÁN TOUR

Đây là dự án được xây dựng theo mô hình MVC sử dụng **PHP 8+**.
Mã nguồn đã bao gồm sẵn các thư viện cần thiết, vì vậy việc cài đặt rất đơn giản.

---

## 1. Yêu cầu hệ thống (Bắt buộc)
- **XAMPP / WAMP** hoặc môi trường tương đương (PHP 8.1 trở lên, MySQL 8.x).

---

## 2. Các bước cài đặt chi tiết

### Bước 1: Khởi tạo mã nguồn
- Giải nén file nộp bài vào thư mục root của web server.
  - Ví dụ đối với XAMPP: Giải nén vào thư mục `C:\xampp\htdocs\tour`.
- Không cần cài đặt thêm bất kỳ thư viện bên ngoài nào vì mọi thứ đã được đóng gói kèm.

### Bước 2: Thiết lập Cơ sở dữ liệu (Database)
1. Mở XAMPP Control Panel, bật **Apache** và **MySQL**.
2. Truy cập vào **phpMyAdmin** (thường là `http://localhost/phpmyadmin`).
3. Tạo một Database mới, đặt tên tùy ý (Ví dụ: `tour_db`), chọn bảng mã `utf8mb4_general_ci`.
4. Import cơ sở dữ liệu:
   - Vào thư mục `database/` trong mã nguồn.
   - Import file `schema.sql` **trước** để tạo các bảng (tables).
   - Import file `seed.sql` **sau** để nạp dữ liệu mẫu ban đầu (danh mục, admin, tours...).

### Bước 3: Cấu hình kết nối (.env)
- Mở file `.env` ở thư mục gốc của dự án.
- Cập nhật lại thông tin kết nối CSDL tại các dòng sau cho khớp với máy tính của thầy/cô:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=tour_db        # Tên Database vừa tạo ở Bước 2
DB_USER=root           # Tên user MySQL (thường là root)
DB_PASSWORD=           # Mật khẩu MySQL (nếu dùng XAMPP thì để trống)
```

---

## 3. Khởi chạy dự án

Bạn có thể khởi chạy dự án theo 1 trong 2 cách sau:

### Cách 1: Chạy qua XAMPP (Khuyên dùng)
1. Mở bảng điều khiển XAMPP Control Panel.
2. Bấm nút **Start** ở 2 mục **Apache** và **MySQL**.
3. Đảm bảo thư mục code đã nằm đúng trong `htdocs` (ví dụ: `C:\xampp\htdocs\tour`).

### Cách 2: Chạy bằng lệnh PHP tích hợp sẵn (PHP Built-in Server)
Nếu bạn không dùng thư mục `htdocs` của XAMPP, bạn có thể chạy trực tiếp bằng lệnh:
1. Mở Terminal / Command Prompt tại thư mục gốc của dự án.
2. Gõ lệnh sau để khởi chạy server:
   ```bash
   php -S localhost:8000 -t public public/router.php
   ```
   *(Lưu ý: Bạn vẫn phải bật MySQL trên XAMPP để có kết nối database)*

---

## 4. Truy cập website

### Truy cập Trang Khách (Client)
- **Nếu chạy bằng Cách 1 (XAMPP):** Mở trình duyệt và truy cập `http://localhost/tour/public/`
- **Nếu chạy bằng Cách 2 (Lệnh PHP):** Mở trình duyệt và truy cập `http://localhost:8000/`

### Truy cập Trang Quản Trị (Admin)
- **Đường dẫn Admin (Nếu dùng XAMPP):** `http://localhost/tour/public/admin/account/login`
- **Đường dẫn Admin (Nếu dùng lệnh PHP):** `http://localhost:8000/admin/account/login`
- **Tài khoản đăng nhập mặc định:**
  - Email: `admin@tour.local`
  - Mật khẩu: `123456`

---

## 4. Lưu ý quan trọng
- **Lỗi 404 Not Found ở trang con:** 
  Đảm bảo file `.htaccess` ở thư mục gốc không bị xóa. File này dùng để điều hướng (URL Rewrite) mọi request về `public/index.php`. Nếu dùng Web Server khác (như Nginx), cần cấu hình lại rewrite rules tương ứng.
