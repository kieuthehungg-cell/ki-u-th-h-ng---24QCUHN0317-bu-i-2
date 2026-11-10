# BUỔI 2: TÀI KHOẢN, XÁC THỰC VÀ PHÂN QUYỀN (PHP MVC - MYSQL)

Dự án học phần thực hành: Website thương mại điện tử cơ bản theo mô hình MVC (PHP 8+, MySQL PDO, Bootstrap 5).

---

## 1. Cấu trúc thư mục

```text
buoi2/
├── app/
│   ├── controllers/
│   │   ├── admin/
│   │   │   ├── AdminAuthController.php         # Đăng nhập / đăng xuất quản trị
│   │   │   ├── AdminCategoryController.php     # Placeholder danh mục (Buổi 3)
│   │   │   ├── AdminDashboardController.php    # Trang tổng quan quản trị
│   │   │   ├── AdminProductController.php      # Placeholder sản phẩm (Buổi 3)
│   │   │   └── AdminUserController.php         # Quản lý người dùng, đổi vai trò, khoá tài khoản
│   │   ├── AccountController.php               # Xem & cập nhật hồ sơ cá nhân, đổi mật khẩu
│   │   ├── AuthController.php                  # Đăng ký, đăng nhập, đăng xuất khách hàng
│   │   └── HomeController.php                  # Trang chủ bán hàng
│   ├── core/
│   │   ├── AdminController.php                 # Controller cơ sở cho khu vực admin (Auth::requireRole)
│   │   ├── Auth.php                            # Lớp quản lý phiên đăng nhập và phân quyền
│   │   ├── Controller.php                      # Base Controller (view, redirect)
│   │   ├── Database.php                        # Kết nối CSDL MySQL qua PDO (Singleton, tự khởi tạo DB)
│   │   ├── Model.php                           # Base Model (find, all, insert, update, delete)
│   │   └── helpers.php                         # Hàm tiện ích (e, base_url, asset, csrf_*, flash)
│   ├── models/
│   │   └── User.php                            # Model người dùng (thừa kế Model, findByEmail)
│   └── views/
│       ├── account/
│       │   └── profile.php                     # Giao diện hồ sơ cá nhân
│       ├── admin/
│       │   ├── dashboard/
│       │   │   └── index.php                   # Giao diện tổng quan quản trị
│       │   ├── user/
│       │   │   └── index.php                   # Danh sách người dùng, form đổi vai trò & khoá tài khoản
│       │   ├── login.php                       # Giao diện đăng nhập quản trị
│       │   └── placeholder.php                 # Trang giữ chỗ cho Danh mục / Sản phẩm (Buổi 3)
│       ├── auth/
│       │   ├── login.php                       # Form đăng nhập khách hàng
│       │   └── register.php                    # Form đăng ký khách hàng
│       ├── errors/
│       │   ├── 403.php                         # Trang lỗi 403 - Forbidden
│       │   └── 404.php                         # Trang lỗi 404 - Not Found
│       ├── home/
│       │   └── index.php                       # Giao diện trang chủ
│       └── layouts/
│           ├── admin.php                       # Layout giao diện quản trị
│           └── main.php                        # Layout giao diện người dùng
├── config/
│   └── database.php                            # Cấu hình kết nối MySQL (host, port, dbname, user, pass)
├── database/
│   └── schema.sql                              # Cấu trúc CSDL và dữ liệu mẫu 2 tài khoản quản trị
├── public/
│   ├── css/
│   │   └── style.css                           # Tệp CSS tuỳ biến giao diện
│   ├── .htaccess                               # Cấu hình mod_rewrite dẫn vào index.php
│   └── index.php                               # Front Controller & Autoloader & Router
├── .htaccess                                   # Điều hướng tự động vào public/
├── index.php                                   # Điều hướng tự động vào public/
└── README.md                                   # Tài liệu hướng dẫn
```

---

## 2. Hướng dẫn cài đặt Cơ sở dữ liệu

1. Mở **XAMPP Control Panel**, khởi động dịch vụ **Apache** và **MySQL**.
2. Mở trình duyệt truy cập: `http://localhost/phpmyadmin/`.
3. Tạo cơ sở dữ liệu mới mang tên `webthuongmai_buoi2` với collation `utf8mb4_unicode_ci`.
4. Chọn tab **Import (Nhập)**, chọn tệp `database/schema.sql` trong thư mục dự án và bấm **Import (Thực hiện)**.
*(Hệ thống cũng được tích hợp cơ chế tự động tạo database và nạp schema nếu truy cập ứng dụng lần đầu).*

---

## 3. Tài khoản mẫu có sẵn trong hệ thống

| Email | Mật khẩu gốc | Vai trò (`role`) | Trạng thái (`status`) | Quyền hạn |
|---|---|---|---|---|
| `admin@shop.local` | `admin123` | `admin` | `active` | Toàn quyền quản trị, đổi vai trò người dùng, khoá/mở khoá tài khoản |
| `staff@shop.local` | `staff123` | `staff` | `active` | Quy cập trang quản trị, xem danh sách người dùng, khoá/mở khoá tài khoản (không đổi vai trò) |
| Tài khoản tạo mới qua Đăng ký | *(Mật khẩu tự đặt)* | `customer` | `active` | Khách hàng: xem hồ sơ cá nhân, đổi mật khẩu, không thể vào `/admin/` |

---

## 4. Các đường dẫn kiểm thử hệ thống

- **Trang chủ:** `http://localhost/webthuongmai/buoi2/public/`
- **Đăng ký khách hàng:** `http://localhost/webthuongmai/buoi2/public/auth/register`
- **Đăng nhập khách hàng:** `http://localhost/webthuongmai/buoi2/public/auth/login`
- **Hồ sơ cá nhân:** `http://localhost/webthuongmai/buoi2/public/account/profile`
- **Đăng nhập quản trị:** `http://localhost/webthuongmai/buoi2/public/admin/auth/login`
- **Trang tổng quan quản trị:** `http://localhost/webthuongmai/buoi2/public/admin/dashboard`
- **Quản lý người dùng:** `http://localhost/webthuongmai/buoi2/public/admin/user`

---

## 5. Kịch bản kiểm tra bảo mật (Theo yêu cầu Buổi 2)

### 5.1. Kiểm tra chống XSS (Cross-Site Scripting)
1. Truy cập `auth/register`, nhập họ tên là: `<script>alert(1)</script>`.
2. Đăng ký thành công và đăng nhập.
3. Quan sát họ tên trên thanh menu và trong trang danh sách người dùng admin: Trình duyệt hiển thị nguyên bản chuỗi ký tự `<script>alert(1)</script>`, hoàn toàn không thực thi mã JavaScript độc hại vì đã được xử lý qua hàm `e()` (`htmlspecialchars`).

### 5.2. Kiểm tra chống CSRF (Cross-Site Request Forgery)
1. Đăng nhập bằng tài khoản quản trị `admin@shop.local`.
2. Mở trình duyệt ấn `F12` -> tab **Console**, chạy đoạn mã fetch giả mạo không kèm token:
```javascript
fetch('/webthuongmai/buoi2/public/admin/user/status', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'user_id=1&status=locked'
}).then(r => r.text()).then(console.log);
```
3. Kết quả: Máy chủ từ chối với mã **HTTP 419** cùng thông báo *"Phiên làm việc đã hết hạn hoặc yêu cầu không hợp lệ. Vui lòng tải lại trang và thử lại."*.

### 5.3. Checklist nghiệm thu cuối buổi
- [x] Khách chưa đăng nhập vào `/admin/...` bị chuyển hướng đến trang đăng nhập quản trị.
- [x] Tài khoản `customer` cố truy cập `/admin/...` bị từ chối với trang lỗi **403**.
- [x] Mật khẩu trong bảng `users` luôn là chuỗi mã hoá bcrypt (`$2y$10$...`), tuyệt đối không lưu chữ rõ.
- [x] `staff` xem được danh sách người dùng nhưng không đổi được vai trò; `admin` thực hiện được đầy đủ.
- [x] Không tài khoản nào có thể tự khoá hoặc tự đổi vai trò của chính mình.
- [x] Mọi form POST (đăng ký, đăng nhập, cập nhật hồ sơ, đổi vai trò, khoá tài khoản) đều được bảo vệ bởi `csrf_field()` và kiểm tra phía server bằng `verify_csrf()`.
