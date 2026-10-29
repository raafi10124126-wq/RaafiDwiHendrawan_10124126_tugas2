<?php
require_once 'config/init.php';
require_login();

$data = ['kode_barang' => '', 'nama_barang' => '', 'kategori' => '', 'jumlah' => '', 'harga' => '', 'lokasi' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $errors['form'] = t('err_csrf');
    } else {
        [$data, $errors] = validate_barang($_POST);

        // Cek kode unik
        if (!isset($errors['kode_barang'])) {
            $cek = $db->prepare('SELECT id FROM barang WHERE kode_barang = ? LIMIT 1');
            $cek->bind_param('s', $data['kode_barang']);
            $cek->execute();
            if ($cek->get_result()->num_rows > 0) {
                $errors['kode_barang'] = t('err_duplicate');
            }
        }

        // CREATE: simpan jika tidak ada error
        if (empty($errors)) {
            $stmt = $db->prepare('INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, harga, lokasi) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('sssids', $data['kode_barang'], $data['nama_barang'], $data['kategori'], $data['jumlah'], $data['harga'], $data['lokasi']);
            $stmt->execute();
            set_flash('success', t('ok_created'));
            header('Location: index.php');
            exit;
        }
    }
}

$page_title = t('add_title');
$submit_label = t('btn_save');
include 'includes/header.php';
?>
<h1><?= e(t('add_title')) ?></h1>
<?php if (isset($errors['form'])): ?><div class="alert alert-error"><?= e($errors['form']) ?></div><?php endif; ?>
<?php include 'includes/form_barang.php'; ?>
<?php?>
