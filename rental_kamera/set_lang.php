<?php
require_once __DIR__ . '/config/auth.php';

$bahasa_tersedia = array('id', 'en');

// Simpan pilihan bahasa di session (hanya nilai whitelist yang diterima)
if (isset($_GET['lang']) && in_array($_GET['lang'], $bahasa_tersedia, true)) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Kembali ke halaman asal; hanya path internal yang diizinkan (cegah open redirect)
$kembali = (isset($_GET['kembali']) && is_string($_GET['kembali'])) ? $_GET['kembali'] : '';

if (
    $kembali === ''
    || $kembali[0] !== '/'
    || strpos($kembali, '//') === 0
    || strpos($kembali, '\\') !== false
    || preg_match('/[\r\n]/', $kembali)
) {
    $kembali = 'index.php';
}

header('Location: ' . $kembali);
exit;