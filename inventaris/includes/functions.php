<?php
// Escape output HTML (sanitasi saat menampilkan data)
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Terjemahan teks berdasarkan bahasa aktif
function t(string $key): string
{
    global $LANG;
    return $LANG[$key] ?? $key;
}

// Proteksi halaman: wajib login
function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

// Flash message (pesan sekali tampil)
function set_flash(string $type, string $msg): void
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function get_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

// CSRF token
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

// Daftar kategori yang diperbolehkan
function kategori_list(): array
{
    return ['elektronik', 'alat_tulis', 'furnitur', 'perlengkapan'];
}

// Label status stok memakai struktur switch
function status_stok(int $jumlah): string
{
    switch (true) {
        case $jumlah === 0:
            return t('stock_empty');
        case $jumlah <= 5:
            return t('stock_low');
        default:
            return t('stock_ok');
    }
}

/**
 * Validasi + sanitasi input form barang.
 * Mengembalikan [$data_bersih, $errors].
 */
function validate_barang(array $in): array
{
    $errors = [];

    // Sanitasi: trim + hapus tag HTML
    $data = [
        'kode_barang' => strtoupper(trim(strip_tags($in['kode_barang'] ?? ''))),
        'nama_barang' => trim(strip_tags($in['nama_barang'] ?? '')),
        'kategori'    => trim($in['kategori'] ?? ''),
        'lokasi'      => trim(strip_tags($in['lokasi'] ?? '')),
        'jumlah'      => trim($in['jumlah'] ?? ''),
        'harga'       => trim($in['harga'] ?? ''),
    ];

    // Kode barang
    if ($data['kode_barang'] === '') {
        $errors['kode_barang'] = t('err_required');
    } elseif (!preg_match('/^[A-Z0-9\-]{3,20}$/', $data['kode_barang'])) {
        $errors['kode_barang'] = t('err_code_format');
    }

    // Nama barang
    if ($data['nama_barang'] === '') {
        $errors['nama_barang'] = t('err_required');
    } elseif (mb_strlen($data['nama_barang']) > 100) {
        $errors['nama_barang'] = t('err_too_long');
    }

    // Kategori
    if ($data['kategori'] === '') {
        $errors['kategori'] = t('err_required');
    } elseif (!in_array($data['kategori'], kategori_list(), true)) {
        $errors['kategori'] = t('err_invalid');
    }

    // Lokasi
    if ($data['lokasi'] === '') {
        $errors['lokasi'] = t('err_required');
    } elseif (mb_strlen($data['lokasi']) > 100) {
        $errors['lokasi'] = t('err_too_long');
    }

    // Jumlah (integer >= 0) memakai filter_var
    if ($data['jumlah'] === '') {
        $errors['jumlah'] = t('err_required');
    } else {
        $jumlah = filter_var($data['jumlah'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
        if ($jumlah === false) {
            $errors['jumlah'] = t('err_number');
        } else {
            $data['jumlah'] = $jumlah;
        }
    }

    // Harga (angka >= 0) memakai filter_var
    if ($data['harga'] === '') {
        $errors['harga'] = t('err_required');
    } else {
        $harga = filter_var($data['harga'], FILTER_VALIDATE_FLOAT);
        if ($harga === false || $harga < 0) {
            $errors['harga'] = t('err_number');
        } else {
            $data['harga'] = $harga;
        }
    }

    return [$data, $errors];
}
