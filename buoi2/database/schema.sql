-- ====================================================================
-- CO SO DU LIEU: Website thuong mai dien tu co ban (PHP thuan - MVC - MySQL)
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `webthuongmai_buoi2` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `webthuongmai_buoi2`;

-- Bang nguoi dung
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` VARCHAR(255) DEFAULT NULL,
    `role` ENUM('admin', 'staff', 'customer') NOT NULL DEFAULT 'customer',
    `status` ENUM('active', 'locked') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bang danh muc (dung cho Buoi 3)
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bang san pham (dung cho Buoi 3)
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT DEFAULT NULL,
    `name` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `image` VARCHAR(255) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Du lieu danh muc mau
INSERT IGNORE INTO `categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Điện thoại & Tablet', 'dien-thoai-tablet', 'Các thiết bị di động, smartphone và máy tính bảng'),
(2, 'Laptop & Máy tính', 'laptop-may-tinh', 'Laptop văn phòng, gaming và máy tính để bàn'),
(3, 'Phụ kiện công nghệ', 'phu-kien', 'Tai nghe, bàn phím, chuột và phụ kiện khác');

-- ===================== BUOI 2 =====================
-- 2 tai khoan quan tri mau.
-- Mat khau goc: admin@shop.local / admin123 va staff@shop.local / staff123
-- Cot password luu sẵn chuoi hash bcrypt (tao boi password_hash()),
-- KHONG phai mat khau that - khong the suy nguoc lai mat khau tu hash nay.
INSERT INTO users (full_name, email, password, role, status) VALUES
('Quản trị viên', 'admin@shop.local', 
'$2y$10$3bcAYXYYxv7TuWI9KH3zOOSDGTT8Q7P/ptvLmboC/WyXdJRWkVYku', 'admin', 
'active'),
('Nhân viên bán hàng', 'staff@shop.local', 
'$2y$10$Q2u2cILb1athiBr5Nz9njOJSvQwfjB333zsvP4omlakUD9rCTpBO2', 'staff', 
'active');
