# Sistem Informasi Inventaris Barang (PHP Murni + MySQL)

Tugas 2 - Sistem Informasi Manajemen Data Sederhana.

## Fitur
- **Form & validasi**: form POST, validasi if-else/switch, sanitasi `strip_tags`, `filter_var`, `htmlspecialchars`
- **CRUD** barang (Create, Read + pencarian, Update, Delete) memakai `mysqli` prepared statement
- **Autentikasi** berbasis `$_SESSION` (password di-hash dengan `password_hash`/`password_verify`), proteksi CSRF
- **Dual bahasa** Indonesia / Inggris, pilihan disimpan di `$_SESSION['lang']`

## Cara Menjalankan
1. Pasang XAMPP / Laragon (PHP 8.0+ dan MySQL/MariaDB).
2. Salin folder proyek ke `htdocs` (XAMPP) atau `www` (Laragon).
3. Impor `database.sql` lewat phpMyAdmin (atau `mysql -u root -p < database.sql`).
4. Sesuaikan kredensial di `config/database.php` bila perlu.
5. Buka `http://localhost/inventaris/`.

Akun demo: **admin** / **admin123**

## Struktur
```
inventaris/
├── config/      database.php, init.php
├── includes/    functions.php, header.php, footer.php, form_barang.php
├── lang/        id.php, en.php
├── assets/      style.css
├── login.php  logout.php  set_lang.php
├── index.php  create.php  edit.php  delete.php
└── database.sql
```
