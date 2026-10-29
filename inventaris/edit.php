<?php
require_once 'config/init.php';
require_login();

// Identitas unik: id dari query string, divalidasi sebagai integer
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('error', t('err_notfound'));
    header('Location: index.php');
    exit;
}

$stmt = $db->prepare('SELECT * FROM barang WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    set_flash('error', t('err_notfound'));
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $errors['form'] = t('err_csrf');
    } else {
        [$data, $errors] = validate_barang($_POST);

        // Kode harus unik, kecuali milik barang ini sendiri
        if (!isset($errors['kode_barang'])) {
            $cek = $db->prepare('SELECT id FROM barang WHERE kode_barang = ? AND id <> ? LIMIT 1');
            $cek->bind_param('si', $data['kode_barang'], $id);
            $cek->execute();
            if ($cek->get_result()->num_rows > 0) {
                $errors['kode_barang'] = t('err_duplicate');
            }
        }

        // UPDATE
        if (empty($errors)) {
            $upd = $db->prepare('UPDATE barang SET kode_barang = ?, nama_barang = ?, kategori = ?, jumlah = ?, harga = ?, lokasi = ? WHERE id = ?');
            $upd->bind_param('sssidsi', $data['kode_barang'], $data['nama_barang'], $data['kategori'], $data['jumlah'], $data['harga'], $data['lokasi'], $id);
            $upd->execute();
            set_flash('success', t('ok_updated'));
            header('Location: index.php');
            exit;
        }
    }
}

$page_title = t('edit_title');
$submit_label = t('btn_update');
include 'includes/header.php';
?>
<h1><?= e(t('edit_title')) ?></h1>
<?php if (isset($errors['form'])): ?><div class="alert alert-error"><?= e($errors['form']) ?></div><?php endif; ?>
<?php include 'includes/form_barang.php'; ?>
<?php include 'includes/footer.php'; ?>
