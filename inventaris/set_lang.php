<?php
require_once 'config/init.php';

// Simpan pilihan bahasa ke session, lalu kembali ke halaman sebelumnya
$lang = $_GET['lang'] ?? '';
if (in_array($lang, $supported_langs, true)) {
    $_SESSION['lang'] = $lang;
}

// Cegah open redirect: hanya izinkan redirect ke halaman di server ini
$back = 'index.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $ref = parse_url($_SERVER['HTTP_REFERER']);
    if (($ref['host'] ?? '') === ($_SERVER['HTTP_HOST'] ?? '') || ($ref['host'] ?? '') === explode(':', $_SERVER['HTTP_HOST'] ?? '')[0]) {
        $back = $_SERVER['HTTP_REFERER'];
    }
}
header('Location: ' . $back);
exit;
