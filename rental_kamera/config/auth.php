<?php
// =====================================================
// config/auth.php — session, helper keamanan, validasi, otentikasi
// =====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error mysqli dilempar sebagai exception supaya bisa ditangkap dengan try/catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/** Escape output HTML (mencegah XSS). */
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

/** Sanitasi input: trim, buang tag HTML, dan buang karakter kontrol. */
function bersihkan($teks, $banyak_baris = false)
{
    $teks = trim((string) $teks);
    $teks = strip_tags($teks);

    if ($banyak_baris) {
        $pola = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u'; // pertahankan tab dan baris baru
    } else {
        $pola = '/[\x00-\x1F\x7F]/u';
    }

    $teks = preg_replace($pola, '', $teks);
    return trim((string) $teks);
}

/** Ambil satu nilai dari $_POST yang sudah disanitasi. */
function ambil_post($nama, $banyak_baris = false)
{
    if (isset($_POST[$nama]) && is_string($_POST[$nama])) {
        return bersihkan($_POST[$nama], $banyak_baris);
    }
    return '';
}

// ---------- CSRF ----------
function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_input()
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid()
{
    $kirim = isset($_POST['csrf']) ? $_POST['csrf'] : '';
    return is_string($kirim)
        && isset($_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $kirim);
}

// ---------- Pesan sekali tampil (flash) ----------
// Yang disimpan adalah kunci terjemahan, sehingga pesan mengikuti bahasa aktif.
function set_flash($tipe, $kunci, $param = array())
{
    $_SESSION['flash'] = array('tipe' => $tipe, 'kunci' => $kunci, 'param' => $param);
}

function ambil_flash()
{
    $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
    unset($_SESSION['flash']);
    return $flash;
}

// ---------- Otentikasi ----------
function sudah_login()
{
    return !empty($_SESSION['user_id']);
}

/** Panggil di awal halaman yang harus login dulu. */
function cek_login()
{
    if (!sudah_login()) {
        header('Location: login.php');
        exit;
    }
}

// ---------- Pemilihan modul (tabel yang dikelola) ----------
/** Modul aktif dari ?m=... (atau POST 'm'); hanya 'kamera' atau 'peminjaman'. */
function ambil_modul()
{
    $modul = '';

    if (isset($_POST['m']) && is_string($_POST['m'])) {
        $modul = $_POST['m'];
    } elseif (isset($_GET['m']) && is_string($_GET['m'])) {
        $modul = $_GET['m'];
    }

    return in_array($modul, array('kamera', 'peminjaman'), true) ? $modul : 'peminjaman';
}

// ---------- Validasi ----------
function tanggal_valid($teks)
{
    $d = DateTime::createFromFormat('Y-m-d', $teks);
    return $d && $d->format('Y-m-d') === $teks;
}

/**
 * Validasi data kamera.
 * Mengembalikan array error: ['kolom' => ['kunci_pesan', [parameter]]]; kosong = valid.
 */
function validasi_kamera($data)
{
    $error = array();

    // Teks wajib diisi dan tidak melebihi panjang kolom (if-else)
    $batas = array('nama_kamera' => 100, 'merek' => 50);
    foreach ($batas as $kolom => $maks) {
        if ($data[$kolom] === '') {
            $error[$kolom] = array('err_wajib', array());
        } elseif (mb_strlen($data[$kolom]) > $maks) {
            $error[$kolom] = array('err_maks', array('maks' => $maks));
        }
    }

    // Jenis hanya boleh salah satu dari enum di database (switch)
    switch ($data['jenis']) {
        case 'DSLR':
        case 'Mirrorless':
        case 'Pocket':
        case 'Action':
            break;
        default:
            $error['jenis'] = array('err_pilihan', array());
    }

    // Harga sewa dan stok: bilangan bulat >= 0
    $angka = array('harga_sewa' => 100000000, 'stok' => 1000);
    foreach ($angka as $kolom => $maks) {
        if ($data[$kolom] === '') {
            $error[$kolom] = array('err_wajib', array());
        } elseif (filter_var($data[$kolom], FILTER_VALIDATE_INT,
            array('options' => array('min_range' => 0, 'max_range' => $maks))) === false) {
            $error[$kolom] = array('err_angka', array('maks' => $maks));
        }
    }

    return $error;
}

/** Validasi data peminjaman. */
function validasi_peminjaman($data)
{
    $error = array();

    // Nama peminjam
    if ($data['nama_peminjam'] === '') {
        $error['nama_peminjam'] = array('err_wajib', array());
    } elseif (mb_strlen($data['nama_peminjam']) > 100) {
        $error['nama_peminjam'] = array('err_maks', array('maks' => 100));
    }

    // Nomor HP
    if ($data['no_hp'] === '') {
        $error['no_hp'] = array('err_wajib', array());
    } elseif (mb_strlen($data['no_hp']) > 20) {
        $error['no_hp'] = array('err_maks', array('maks' => 20));
    } elseif (!preg_match('/^\+?[0-9][0-9 -]{6,18}$/', $data['no_hp'])) {
        $error['no_hp'] = array('err_hp', array());
    }

    // Kamera yang dipinjam (harus ID angka; keberadaannya dicek database lewat foreign key)
    if (filter_var($data['kamera_id'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))) === false) {
        $error['kamera_id'] = array('err_pilihan', array());
    }

    // Tanggal pinjam
    if ($data['tgl_pinjam'] === '') {
        $error['tgl_pinjam'] = array('err_wajib', array());
    } elseif (!tanggal_valid($data['tgl_pinjam'])) {
        $error['tgl_pinjam'] = array('err_tanggal', array());
    }

    // Tanggal kembali (tidak boleh sebelum tanggal pinjam)
    if ($data['tgl_kembali'] === '') {
        $error['tgl_kembali'] = array('err_wajib', array());
    } elseif (!tanggal_valid($data['tgl_kembali'])) {
        $error['tgl_kembali'] = array('err_tanggal', array());
    } elseif (!isset($error['tgl_pinjam']) && $data['tgl_kembali'] < $data['tgl_pinjam']) {
        $error['tgl_kembali'] = array('err_tgl_urut', array());
    }

    // Status hanya boleh salah satu dari enum di database (switch)
    switch ($data['status']) {
        case 'Dipinjam':
        case 'Dikembalikan':
        case 'Terlambat':
            break;
        default:
            $error['status'] = array('err_pilihan', array());
    }

    // Catatan bersifat opsional
    if (mb_strlen($data['catatan']) > 500) {
        $error['catatan'] = array('err_maks', array('maks' => 500));
    }

    return $error;
}

/** Tampilkan pesan error milik satu kolom form. */
function error_field($error, $kolom)
{
    if (!isset($error[$kolom])) {
        return '';
    }
    return '<small class="err">' . t($error[$kolom][0], $error[$kolom][1]) . '</small>';
}