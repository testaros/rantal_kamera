<?php
require_once __DIR__ . '/config/auth.php';

// Logout hanya lewat POST dengan token CSRF yang valid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valid()) {
    // Hapus identitas login; pilihan bahasa ($_SESSION['lang']) sengaja dipertahankan
    unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['csrf']);
    session_regenerate_id(true);
    set_flash('sukses', 'msg_keluar');
}

header('Location: login.php');
exit;