<?php
/* Form bersama untuk Create dan Update.
   Variabel: $data (array nilai), $errors (array), $submit_label */
$field = fn($k) => e($data[$k] ?? '');
$err   = fn($k) => isset($errors[$k]) ? '<small class="error">' . e($errors[$k]) . '</small>' : '';
?>
<form method="POST" novalidate class="card form">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <div class="form-group">
        <label for="kode_barang"><?= e(t('f_code')) ?></label>
        <input type="text" id="kode_barang" name="kode_barang" value="<?= $field('kode_barang') ?>" maxlength="20" placeholder="BRG-001">
        <?= $err('kode_barang') ?>
    </div>

    <div class="form-group">
        <label for="nama_barang"><?= e(t('f_name')) ?></label>
        <input type="text" id="nama_barang" name="nama_barang" value="<?= $field('nama_barang') ?>" maxlength="100">
        <?= $err('nama_barang') ?>
    </div>

    <div class="form-group">
        <label for="kategori"><?= e(t('f_category')) ?></label>
        <select id="kategori" name="kategori">
            <option value=""><?= e(t('choose')) ?></option>
            <?php foreach (kategori_list() as $k): ?>
                <option value="<?= e($k) ?>" <?= ($data['kategori'] ?? '') === $k ? 'selected' : '' ?>><?= e(t('cat_' . $k)) ?></option>
            <?php endforeach; ?>
        </select>
        <?= $err('kategori') ?>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="jumlah"><?= e(t('f_qty')) ?></label>
            <input type="number" id="jumlah" name="jumlah" value="<?= $field('jumlah') ?>" min="0">
            <?= $err('jumlah') ?>
        </div>
        <div class="form-group">
            <label for="harga"><?= e(t('f_price')) ?> (Rp)</label>
            <input type="number" id="harga" name="harga" value="<?= $field('harga') ?>" min="0" step="0.01">
            <?= $err('harga') ?>
        </div>
    </div>

    <div class="form-group">
        <label for="lokasi"><?= e(t('f_location')) ?></label>
        <input type="text" id="lokasi" name="lokasi" value="<?= $field('lokasi') ?>" maxlength="100">
        <?= $err('lokasi') ?>
    </div>

    <div class="actions">
        <button type="submit" class="btn"><?= e($submit_label) ?></button>
        <a href="index.php" class="btn btn-gray"><?= e(t('btn_cancel')) ?></a>
    </div>
</form>
