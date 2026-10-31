<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/auth.php';
cek_login();

$modul = ambil_modul();

// Penghapusan hanya diterima lewat POST dan wajib membawa token CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?m=' . $modul);
    exit;
}

if (!csrf_valid()) {
    set_flash('error', 'err_csrf');
    header('Location: index.php?m=' . $modul);
    exit;
}

$id = (isset($_POST['id']) && is_string($_POST['id']) && ctype_digit($_POST['id'])) ? (int) $_POST['id'] : 0;

if ($id < 1) {
    set_flash('error', 'err_tidak_ada');
    header('Location: index.php?m=' . $modul);
    exit;
}

try {
    if ($modul === 'kamera') {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM kamera WHERE id_kamera = ?");
    } else {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM peminjaman WHERE id = ?");
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $terhapus = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($terhapus > 0) {
        set_flash('sukses', 'msg_hapus');
    } else {
        set_flash('error', 'err_tidak_ada');
    }
} catch (mysqli_sql_exception $ex) {
    if ($ex->getCode() == 1451) { // foreign key: kamera masih dipakai di tabel peminjaman
        set_flash('error', 'err_kamera_dipakai');
    } else {
        set_flash('error', 'err_db');
    }
}

header('Location: index.php?m=' . $modul);
exit;