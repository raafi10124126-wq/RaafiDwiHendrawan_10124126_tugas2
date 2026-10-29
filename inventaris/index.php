<?php
require_once 'config/init.php';
require_login();

// READ: ambil data (dengan pencarian opsional) memakai prepared statement
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = $db->prepare('SELECT * FROM barang WHERE kode_barang LIKE ? OR nama_barang LIKE ? ORDER BY id DESC');
    $stmt->bind_param('ss', $like, $like);
} else {
    $stmt = $db->prepare('SELECT * FROM barang ORDER BY id DESC');
}
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$page_title = t('list_title');
include 'includes/header.php';
?>
<div class="page-head">
    <h1><?= e(t('list_title')) ?></h1>
    <a href="create.php" class="btn">+ <?= e(t('nav_add')) ?></a>
</div>

<form method="GET" class="search">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="<?= e(t('search_ph')) ?>">
    <button type="submit" class="btn"><?= e(t('btn_search')) ?></button>
</form>

<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th><?= e(t('col_no')) ?></th>
            <th><?= e(t('col_code')) ?></th>
            <th><?= e(t('col_name')) ?></th>
            <th><?= e(t('col_category')) ?></th>
            <th class="num"><?= e(t('col_qty')) ?></th>
            <th class="num"><?= e(t('col_price')) ?></th>
            <th><?= e(t('col_location')) ?></th>
            <th><?= e(t('col_status')) ?></th>
            <th><?= e(t('col_action')) ?></th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($rows) === 0): ?>
        <tr><td colspan="9" class="center muted"><?= e(t('no_data')) ?></td></tr>
    <?php else: foreach ($rows as $i => $r): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($r['kode_barang']) ?></td>
            <td><?= e($r['nama_barang']) ?></td>
            <td><?= e(t('cat_' . $r['kategori'])) ?></td>
            <td class="num"><?= (int)$r['jumlah'] ?></td>
            <td class="num">Rp <?= number_format((float)$r['harga'], 0, ',', '.') ?></td>
            <td><?= e($r['lokasi']) ?></td>
            <td><span class="badge badge-<?= (int)$r['jumlah'] === 0 ? 'red' : ((int)$r['jumlah'] <= 5 ? 'yellow' : 'green') ?>"><?= e(status_stok((int)$r['jumlah'])) ?></span></td>
            <td class="actions-cell">
                <a href="edit.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm"><?= e(t('btn_edit')) ?></a>
                <form method="POST" action="delete.php" class="inline" onsubmit="return confirm('<?= e(addslashes(t('confirm_delete'))) ?>')">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-red"><?= e(t('btn_delete')) ?></button>
                </form>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
</div>
<?php?>
