<?php
require_once 'config/init.php';
require_login();

// DELETE hanya lewat POST + token CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    set_flash('error', t('err_csrf'));
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $db->prepare('DELETE FROM barang WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    set_flash($stmt->affected_rows > 0 ? 'success' : 'error', $stmt->affected_rows > 0 ? t('ok_deleted') : t('err_notfound'));
} else {
    set_flash('error', t('err_notfound'));
}
header('Location: index.php');
exit;
