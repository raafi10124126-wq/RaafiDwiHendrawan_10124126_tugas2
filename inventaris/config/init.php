<?php
// Dimuat di awal setiap halaman: session, bahasa, database, fungsi bantu
session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- Pengaturan bahasa (disimpan di $_SESSION['lang']) ---
$supported_langs = ['id', 'en'];
if (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], $supported_langs, true)) {
    $_SESSION['lang'] = 'id'; // bahasa default
}
$LANG = require __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';
