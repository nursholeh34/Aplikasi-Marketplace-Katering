-- =====================================================================
-- Marketplace Katering - skema + data contoh (MySQL 8 / MariaDB 10.4+)
-- Cara pakai (alternatif dari `php artisan migrate --seed`):
--   1) CREATE DATABASE marketplace_katering CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
--   2) mysql -u root -p marketplace_katering < database/catering.sql
-- Semua akun contoh memakai kata sandi: password
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `menus`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `merchants`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('merchant','customer') NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `merchants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `cuisine_type` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `merchants_user_id_unique` (`user_id`),
  KEY `merchants_city_index` (`city`),
  KEY `merchants_cuisine_type_index` (`cuisine_type`),
  CONSTRAINT `merchants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_user_id_unique` (`user_id`),
  CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `price` int unsigned NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_merchant_id_foreign` (`merchant_id`),
  CONSTRAINT `menus_merchant_id_foreign` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) DEFAULT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `merchant_id` bigint unsigned NOT NULL,
  `delivery_date` date NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `notes` text,
  `total` bigint unsigned NOT NULL DEFAULT '0',
  `status` enum('pending','confirmed','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_invoice_number_unique` (`invoice_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_merchant_id_foreign` (`merchant_id`),
  KEY `orders_status_index` (`status`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_merchant_id_foreign` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `menu_id` bigint unsigned DEFAULT NULL,
  `menu_name` varchar(255) NOT NULL,
  `price` int unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `subtotal` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------- Catatan migrasi (agar `php artisan migrate` tidak mengulang) ----------
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('2024_06_01_000001_create_merchants_table', 1),
('2024_06_01_000002_create_customers_table', 1),
('2024_06_01_000003_create_menus_table', 1),
('2024_06_01_000004_create_orders_table', 1);

-- ---------- Data contoh (kata sandi semua akun: password) ----------
INSERT INTO `users` (`id`,`name`,`email`,`role`,`password`,`created_at`,`updated_at`) VALUES
(1,'Bu Sari','merchant1@example.com','merchant','$2y$12$.kTnaXfIPVDSXNq6vvomY.6ZR5Quf4M7Mwj8wvIKciWfC86m2VnCW','2026-10-15 08:00:00','2026-10-15 08:00:00'),
(2,'Pak Rizal','merchant2@example.com','merchant','$2y$12$.kTnaXfIPVDSXNq6vvomY.6ZR5Quf4M7Mwj8wvIKciWfC86m2VnCW','2026-10-15 08:00:00','2026-10-15 08:00:00'),
(3,'Dewi Lestari','customer@example.com','customer','$2y$12$.kTnaXfIPVDSXNq6vvomY.6ZR5Quf4M7Mwj8wvIKciWfC86m2VnCW','2026-10-15 08:00:00','2026-10-15 08:00:00');

INSERT INTO `merchants` (`id`,`user_id`,`company_name`,`address`,`city`,`phone`,`cuisine_type`,`description`,`created_at`,`updated_at`) VALUES
(1,1,'Dapur Bu Sari','Jl. Kebon Jeruk No. 12','Jakarta','081234567890','Nusantara','Katering rumahan untuk makan siang kantor. Masakan Nusantara, porsi pas, dikirim tepat waktu.','2026-10-15 08:00:00','2026-10-15 08:00:00'),
(2,2,'Pempek & Co','Jl. Jenderal Sudirman No. 88','Palembang','081298765432','Palembang','Hidangan khas Palembang: pempek, tekwan, dan model, dengan cuko asli.','2026-10-15 08:00:00','2026-10-15 08:00:00');

INSERT INTO `customers` (`id`,`user_id`,`company_name`,`address`,`city`,`phone`,`created_at`,`updated_at`) VALUES
(1,3,'PT Maju Bersama','Gedung Graha Lt. 5, Jl. HR Rasuna Said, Jakarta','Jakarta','0215550123','2026-10-15 08:00:00','2026-10-15 08:00:00');

INSERT INTO `menus` (`id`,`merchant_id`,`name`,`description`,`photo`,`price`,`is_available`,`created_at`,`updated_at`) VALUES
(1,1,'Nasi Box Ayam Bakar','Ayam bakar bumbu kecap, nasi, lalapan, sambal terasi, dan tempe goreng.',NULL,28000,1,'2026-10-15 08:00:00','2026-10-15 08:00:00'),
(2,1,'Nasi Box Rendang','Rendang daging sapi empuk, nasi putih, sayur singkong, dan sambal hijau.',NULL,35000,1,'2026-10-15 08:00:00','2026-10-15 08:00:00'),
(3,1,'Nasi Box Ikan Patin Pindang','Pindang patin segar, nasi, tumis kangkung, dan sambal.',NULL,32000,1,'2026-10-15 08:00:00','2026-10-15 08:00:00'),
(4,2,'Paket Pempek Campur','Lenjer, kapal selam, dan adaan dengan cuko khas Palembang (10 pcs).',NULL,30000,1,'2026-10-15 08:00:00','2026-10-15 08:00:00'),
(5,2,'Tekwan Spesial','Tekwan udang dengan kuah kaldu, bengkuang, dan soun.',NULL,25000,1,'2026-10-15 08:00:00','2026-10-15 08:00:00'),
(6,2,'Mi Celor','Mi kuah santan gurih dengan telur rebus dan udang.',NULL,27000,1,'2026-10-15 08:00:00','2026-10-15 08:00:00');

INSERT INTO `orders` (`id`,`invoice_number`,`customer_id`,`merchant_id`,`delivery_date`,`delivery_address`,`notes`,`total`,`status`,`created_at`,`updated_at`) VALUES
(1,'INV-20261015-00001',1,1,'2026-10-20','Gedung Graha Lt. 5, Jl. HR Rasuna Said, Jakarta','Kirim sebelum jam 11.30.',910000,'pending','2026-10-15 08:00:00','2026-10-15 08:00:00');

INSERT INTO `order_items` (`id`,`order_id`,`menu_id`,`menu_name`,`price`,`quantity`,`subtotal`,`created_at`,`updated_at`) VALUES
(1,1,1,'Nasi Box Ayam Bakar',28000,20,560000,'2026-10-15 08:00:00','2026-10-15 08:00:00'),
(2,1,2,'Nasi Box Rendang',35000,10,350000,'2026-10-15 08:00:00','2026-10-15 08:00:00');
