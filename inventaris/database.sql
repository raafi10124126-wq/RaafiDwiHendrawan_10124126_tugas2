-- Ekspor database: Sistem Informasi Inventaris Barang
-- Impor lewat phpMyAdmin atau: mysql -u root -p < database.sql

CREATE DATABASE IF NOT EXISTS db_inventaris
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_inventaris;

DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE barang (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  kode_barang VARCHAR(20) NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  kategori ENUM('elektronik','alat_tulis','furnitur','perlengkapan') NOT NULL,
  jumlah INT UNSIGNED NOT NULL DEFAULT 0,
  harga DECIMAL(12,2) NOT NULL DEFAULT 0,
  lokasi VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_kode (kode_barang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Akun demo: username "admin", password "admin123" (disimpan sebagai hash bcrypt)
INSERT INTO users (username, password, nama) VALUES
('admin', '$2y$10$W.mNDxd6maorgasWo7.BduZFdo9O3rF27iwywiq48iYpl9o1SKMQC', 'Administrator');

INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, harga, lokasi) VALUES
('BRG-001', 'Laptop Asus Vivobook 14', 'elektronik', 8, 8500000, 'Gudang A - Rak 1'),
('BRG-002', 'Proyektor Epson EB-X06', 'elektronik', 3, 6200000, 'Gudang A - Rak 2'),
('BRG-003', 'Pulpen Standard AE7 (Box)', 'alat_tulis', 40, 28000, 'Gudang B - Rak 3'),
('BRG-004', 'Kertas HVS A4 80gr (Rim)', 'alat_tulis', 0, 52000, 'Gudang B - Rak 3'),
('BRG-005', 'Kursi Kantor Ergonomis', 'furnitur', 12, 1250000, 'Gudang C'),
('BRG-006', 'Meja Lipat Serbaguna', 'furnitur', 5, 450000, 'Gudang C'),
('BRG-007', 'Kabel HDMI 5 Meter', 'perlengkapan', 25, 75000, 'Gudang A - Rak 4');
