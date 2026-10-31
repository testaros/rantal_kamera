<?php
// =====================================================
// config/lang.php — kamus dua bahasa (id / en), helper form, dan template tampilan
// =====================================================

require_once __DIR__ . '/auth.php';

$bahasa_tersedia = array('id', 'en');
if (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], $bahasa_tersedia, true)) {
    $_SESSION['lang'] = 'id'; // bahasa bawaan
}

$kamus = array(
    'id' => array(
        'app_judul'       => 'Rimera',
        'footer'          => 'Sistem Informasi Rental Kamera — PHP & MySQL',
        'halo'            => 'Halo, {user}',
        'nav_peminjaman'  => 'Peminjaman',
        'nav_kamera'      => 'Data Kamera',

        'judul_peminjaman'        => 'Data Peminjaman',
        'judul_kamera'            => 'Data Kamera',
        'judul_tambah_peminjaman' => 'Tambah Peminjaman',
        'judul_tambah_kamera'     => 'Tambah Kamera',
        'judul_edit_peminjaman'   => 'Ubah Peminjaman',
        'judul_edit_kamera'       => 'Ubah Data Kamera',
        'judul_login'             => 'Masuk',

        'btn_tambah'      => 'Tambah Data',
        'btn_ubah'        => 'Ubah',
        'btn_hapus'       => 'Hapus',
        'btn_simpan'      => 'Simpan',
        'btn_simpan_ubah' => 'Simpan Perubahan',
        'btn_batal'       => 'Batal',
        'btn_masuk'       => 'Masuk',
        'btn_keluar'      => 'Keluar',

        'kol_no'          => 'No',
        'kol_aksi'        => 'Aksi',

        'f_nama_kamera'   => 'Nama Kamera',
        'f_merek'         => 'Merek',
        'f_jenis'         => 'Jenis',
        'f_harga_sewa'    => 'Harga Sewa / Hari',
        'f_stok'          => 'Stok',
        'f_nama_peminjam' => 'Nama Peminjam',
        'f_no_hp'         => 'No. HP',
        'f_kamera_id'     => 'Kamera',
        'f_tgl_pinjam'    => 'Tanggal Pinjam',
        'f_tgl_kembali'   => 'Tanggal Kembali',
        'f_status'        => 'Status',
        'f_catatan'       => 'Catatan',

        'ph_nama_kamera'   => 'Contoh: EOS 800D',
        'ph_merek'         => 'Contoh: Canon',
        'ph_harga_sewa'    => 'Contoh: 150000',
        'ph_stok'          => 'Contoh: 2',
        'ph_nama_peminjam' => 'Nama lengkap',
        'ph_no_hp'         => 'Contoh: 081234567890',
        'ph_catatan'       => 'Opsional',
        'pilih_kamera'     => '-- Pilih kamera --',

        'status_Dipinjam'     => 'Dipinjam',
        'status_Dikembalikan' => 'Dikembalikan',
        'status_Terlambat'    => 'Terlambat',

        'kosong'           => 'Belum ada data.',
        'total'            => 'Total: {jumlah} data',
        'konfirmasi_hapus' => 'Yakin ingin menghapus {nama}?',

        'f_username'      => 'Username',
        'f_password'      => 'Password',
        'login_judul'     => 'Masuk ke Sistem',
        'login_sub'       => 'Silakan login untuk mengelola data rental kamera.',

        'msg_tambah'      => 'Data {nama} berhasil ditambahkan.',
        'msg_ubah'        => 'Data {nama} berhasil diperbarui.',
        'msg_hapus'       => 'Data berhasil dihapus.',
        'msg_keluar'      => 'Anda telah keluar.',

        'err_wajib'       => 'Kolom ini wajib diisi.',
        'err_maks'        => 'Maksimal {maks} karakter.',
        'err_angka'       => 'Harus berupa angka bulat antara 0 dan {maks}.',
        'err_pilihan'     => 'Pilih salah satu opsi yang valid.',
        'err_hp'          => 'Nomor HP tidak valid (7–19 digit; boleh diawali + dan memuat spasi atau tanda -).',
        'err_tanggal'     => 'Format tanggal tidak valid.',
        'err_tgl_urut'    => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        'err_kamera_tidak_ada' => 'Kamera yang dipilih tidak ditemukan.',
        'err_kamera_dipakai'   => 'Kamera tidak dapat dihapus karena masih tercatat di data peminjaman.',
        'err_tidak_ada'   => 'Data tidak ditemukan.',
        'err_csrf'        => 'Sesi formulir tidak valid. Silakan coba lagi.',
        'err_db'          => 'Terjadi kesalahan database. Periksa koneksi dan tabel.',
        'err_login_kosong' => 'Username dan password wajib diisi.',
        'err_login_gagal' => 'Username atau password salah.',
    ),

    'en' => array(
        'app_judul'       => 'Rimera',
        'footer'          => 'Camera Rental Information System — PHP & MySQL',
        'halo'            => 'Hello, {user}',
        'nav_peminjaman'  => 'Rentals',
        'nav_kamera'      => 'Cameras',

        'judul_peminjaman'        => 'Rental Records',
        'judul_kamera'            => 'Camera Data',
        'judul_tambah_peminjaman' => 'Add Rental',
        'judul_tambah_kamera'     => 'Add Camera',
        'judul_edit_peminjaman'   => 'Edit Rental',
        'judul_edit_kamera'       => 'Edit Camera',
        'judul_login'             => 'Sign In',

        'btn_tambah'      => 'Add Data',
        'btn_ubah'        => 'Edit',
        'btn_hapus'       => 'Delete',
        'btn_simpan'      => 'Save',
        'btn_simpan_ubah' => 'Save Changes',
        'btn_batal'       => 'Cancel',
        'btn_masuk'       => 'Sign In',
        'btn_keluar'      => 'Sign Out',

        'kol_no'          => 'No',
        'kol_aksi'        => 'Actions',

        'f_nama_kamera'   => 'Camera Name',
        'f_merek'         => 'Brand',
        'f_jenis'         => 'Type',
        'f_harga_sewa'    => 'Rental Price / Day',
        'f_stok'          => 'Stock',
        'f_nama_peminjam' => 'Borrower Name',
        'f_no_hp'         => 'Phone',
        'f_kamera_id'     => 'Camera',
        'f_tgl_pinjam'    => 'Borrow Date',
        'f_tgl_kembali'   => 'Return Date',
        'f_status'        => 'Status',
        'f_catatan'       => 'Notes',

        'ph_nama_kamera'   => 'e.g. EOS 800D',
        'ph_merek'         => 'e.g. Canon',
        'ph_harga_sewa'    => 'e.g. 150000',
        'ph_stok'          => 'e.g. 2',
        'ph_nama_peminjam' => 'Full name',
        'ph_no_hp'         => 'e.g. 081234567890',
        'ph_catatan'       => 'Optional',
        'pilih_kamera'     => '-- Select camera --',

        'status_Dipinjam'     => 'Borrowed',
        'status_Dikembalikan' => 'Returned',
        'status_Terlambat'    => 'Overdue',

        'kosong'           => 'No data yet.',
        'total'            => 'Total: {jumlah} records',
        'konfirmasi_hapus' => 'Are you sure you want to delete {nama}?',

        'f_username'      => 'Username',
        'f_password'      => 'Password',
        'login_judul'     => 'Sign in to the System',
        'login_sub'       => 'Please sign in to manage camera rental data.',

        'msg_tambah'      => 'Record {nama} was added successfully.',
        'msg_ubah'        => 'Record {nama} was updated successfully.',
        'msg_hapus'       => 'Record was deleted successfully.',
        'msg_keluar'      => 'You have been signed out.',

        'err_wajib'       => 'This field is required.',
        'err_maks'        => 'Maximum {maks} characters.',
        'err_angka'       => 'Must be a whole number between 0 and {maks}.',
        'err_pilihan'     => 'Please choose a valid option.',
        'err_hp'          => 'Invalid phone number (7–19 digits; may start with + and contain spaces or -).',
        'err_tanggal'     => 'Invalid date format.',
        'err_tgl_urut'    => 'Return date cannot be before the borrow date.',
        'err_kamera_tidak_ada' => 'The selected camera was not found.',
        'err_kamera_dipakai'   => 'This camera cannot be deleted because it is still used in rental records.',
        'err_tidak_ada'   => 'Record not found.',
        'err_csrf'        => 'Invalid form session. Please try again.',
        'err_db'          => 'A database error occurred. Check the connection and tables.',
        'err_login_kosong' => 'Username and password are required.',
        'err_login_gagal' => 'Incorrect username or password.',
    ),
);

/** Ambil teks sesuai bahasa aktif (sudah di-escape), ganti placeholder {nama}. */
function t($kunci, $param = array())
{
    global $kamus;

    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';
    $teks = isset($kamus[$lang][$kunci]) ? $kamus[$lang][$kunci] : $kunci;

    foreach ($param as $nama => $nilai) {
        $teks = str_replace('{' . $nama . '}', (string) $nilai, $teks);
    }

    return e($teks);
}

/** Format harga mengikuti bahasa aktif. */
function format_harga($angka)
{
    if ($_SESSION['lang'] === 'en') {
        return 'IDR ' . number_format((int) $angka, 0, '.', ',');
    }
    return 'Rp ' . number_format((int) $angka, 0, ',', '.');
}

function format_tanggal($tanggal)
{
    $waktu = strtotime((string) $tanggal);
    return $waktu ? date('d-m-Y', $waktu) : '-';
}

// ---------- Helper form (menghasilkan HTML yang sudah aman) ----------

/** Satu baris input teks/tanggal. */
function field_input($kolom, $data, $error, $maks = 100, $tipe = 'text')
{
    global $kamus;

    $kelas = isset($error[$kolom]) ? 'invalid' : '';
    $html  = '<div class="form-group">';
    $html .= '<label for="' . e($kolom) . '">' . t('f_' . $kolom) . '</label>';
    $html .= '<input type="' . e($tipe) . '" id="' . e($kolom) . '" name="' . e($kolom) . '"';
    $html .= ' value="' . e($data[$kolom]) . '" class="' . $kelas . '"';

    if ($tipe === 'text') {
        $html .= ' maxlength="' . (int) $maks . '"';
    }
    if (isset($kamus[$_SESSION['lang']]['ph_' . $kolom])) {
        $html .= ' placeholder="' . t('ph_' . $kolom) . '"';
    }

    $html .= '>' . error_field($error, $kolom) . '</div>';
    return $html;
}

/** Satu baris dropdown. $opsi = ['nilai' => 'label HTML yang sudah aman']. */
function field_select($kolom, $data, $error, $opsi, $kunci_kosong = '')
{
    $kelas = isset($error[$kolom]) ? 'invalid' : '';
    $html  = '<div class="form-group">';
    $html .= '<label for="' . e($kolom) . '">' . t('f_' . $kolom) . '</label>';
    $html .= '<select id="' . e($kolom) . '" name="' . e($kolom) . '" class="' . $kelas . '">';

    if ($kunci_kosong !== '') {
        $html .= '<option value="">' . t($kunci_kosong) . '</option>';
    }

    foreach ($opsi as $nilai => $label) {
        $dipilih = ((string) $nilai === (string) $data[$kolom]) ? ' selected' : '';
        $html .= '<option value="' . e($nilai) . '"' . $dipilih . '>' . $label . '</option>';
    }

    $html .= '</select>' . error_field($error, $kolom) . '</div>';
    return $html;
}

/** Satu baris textarea. */
function field_textarea($kolom, $data, $error, $maks = 500)
{
    global $kamus;

    $kelas = isset($error[$kolom]) ? 'invalid' : '';
    $html  = '<div class="form-group">';
    $html .= '<label for="' . e($kolom) . '">' . t('f_' . $kolom) . '</label>';
    $html .= '<textarea id="' . e($kolom) . '" name="' . e($kolom) . '" rows="3"';
    $html .= ' maxlength="' . (int) $maks . '" class="' . $kelas . '"';

    if (isset($kamus[$_SESSION['lang']]['ph_' . $kolom])) {
        $html .= ' placeholder="' . t('ph_' . $kolom) . '"';
    }

    $html .= '>' . e($data[$kolom]) . '</textarea>' . error_field($error, $kolom) . '</div>';
    return $html;
}

function opsi_jenis()
{
    return array('DSLR' => 'DSLR', 'Mirrorless' => 'Mirrorless', 'Pocket' => 'Pocket', 'Action' => 'Action');
}

function opsi_status()
{
    return array(
        'Dipinjam'     => t('status_Dipinjam'),
        'Dikembalikan' => t('status_Dikembalikan'),
        'Terlambat'    => t('status_Terlambat'),
    );
}

function opsi_kamera($daftar)
{
    $opsi = array();
    foreach ($daftar as $baris) {
        $opsi[$baris['id_kamera']] = e($baris['nama_kamera']) . ' (' . e($baris['merek']) . ')';
    }
    return $opsi;
}

// ---------- Template tampilan ----------

/** Bagian atas halaman: <head>, navigasi, pilihan bahasa, tombol keluar, pesan flash. */
function tampil_header($kunci_judul)
{
    $lang        = $_SESSION['lang'];
    $kembali     = urlencode($_SERVER['REQUEST_URI']);
    $modul_aktif = ambil_modul();
    $flash       = ambil_flash();
    ?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= t($kunci_judul) ?> | <?= t('app_judul') ?></title>
<style>
* { box-sizing: border-box; }
body { margin: 0; font-family: system-ui, -apple-system, "Segoe UI", Arial, sans-serif; background: #f3f4f6; color: #111827; line-height: 1.5; min-height: 100vh; display: flex; flex-direction: column; }
.topbar { background: #111827; color: #fff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
.kiri { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
.brand { font-weight: 700; font-size: 1.1rem; }
.nav a { color: #d1d5db; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: .92rem; }
.nav a.aktif { background: #374151; color: #fff; font-weight: 600; }
.kanan { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
.bahasa { display: flex; border: 1px solid #4b5563; border-radius: 6px; overflow: hidden; }
.bahasa a { color: #d1d5db; text-decoration: none; padding: 4px 12px; font-size: .85rem; }
.bahasa a.aktif { background: #2563eb; color: #fff; font-weight: 600; }
.user { font-size: .9rem; color: #d1d5db; }
.inline { display: inline; margin: 0; }
.container { width: 100%; max-width: 1100px; margin: 24px auto; padding: 0 16px; flex: 1; }
.footer { text-align: center; color: #6b7280; font-size: .85rem; padding: 16px; }
.head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 8px; }
h1 { font-size: 1.5rem; margin: 0 0 4px; }
.muted { color: #6b7280; margin: 0 0 16px; }
.btn { display: inline-block; padding: 8px 16px; border: 1px solid transparent; border-radius: 6px; font-size: .9rem; font-family: inherit; text-decoration: none; cursor: pointer; background: #e5e7eb; color: #111827; }
.btn-primary { background: #2563eb; color: #fff; }
.btn-danger { background: #dc2626; color: #fff; }
.btn-terang { background: #f9fafb; border-color: #e5e7eb; color: #111827; }
.btn-kecil { padding: 5px 12px; font-size: .82rem; }
.btn-block { width: 100%; }
.tabel { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #e5e7eb; }
th { background: #f9fafb; font-size: .82rem; text-transform: uppercase; color: #6b7280; white-space: nowrap; }
tbody tr:last-child td { border-bottom: none; }
td.aksi { white-space: nowrap; }
td.kosong { text-align: center; color: #6b7280; padding: 32px; }
.badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: .8rem; font-weight: 600; }
.badge-Dipinjam { background: #dbeafe; color: #1e40af; }
.badge-Dikembalikan { background: #dcfce7; color: #166534; }
.badge-Terlambat { background: #fee2e2; color: #991b1b; }
.card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; }
.form-group { margin-bottom: 16px; }
label { display: block; font-weight: 600; margin-bottom: 6px; font-size: .9rem; }
input[type="text"], input[type="password"], input[type="date"], select, textarea { width: 100%; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: .95rem; font-family: inherit; background: #fff; }
.invalid { border-color: #dc2626 !important; }
.err { display: block; color: #dc2626; margin-top: 4px; font-size: .82rem; }
.aksi-form { display: flex; gap: 10px; margin-top: 8px; }
.alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: .92rem; }
.alert-sukses { background: #dcfce7; color: #166534; }
.alert-error { background: #fee2e2; color: #991b1b; }
.login { max-width: 400px; margin: 40px auto 0; }
.login .card { margin-top: 12px; }
</style>
</head>
<body>
<header class="topbar">
    <div class="kiri">
        <div class="brand"> <?= t('app_judul') ?></div>
<?php if (sudah_login()): ?>
        <nav class="nav">
            <a href="index.php?m=peminjaman" class="<?= $modul_aktif === 'peminjaman' ? 'aktif' : '' ?>"><?= t('nav_peminjaman') ?></a>
            <a href="index.php?m=kamera" class="<?= $modul_aktif === 'kamera' ? 'aktif' : '' ?>"><?= t('nav_kamera') ?></a>
        </nav>
<?php endif; ?>
    </div>

    <div class="kanan">
        <span class="bahasa">
            <a href="set_lang.php?lang=id&amp;kembali=<?= $kembali ?>" class="<?= $lang === 'id' ? 'aktif' : '' ?>">ID</a>
            <a href="set_lang.php?lang=en&amp;kembali=<?= $kembali ?>" class="<?= $lang === 'en' ? 'aktif' : '' ?>">EN</a>
        </span>
<?php if (sudah_login()): ?>
        <span class="user"><?= t('halo', array('user' => $_SESSION['username'])) ?></span>
        <form method="post" action="logout.php" class="inline">
            <?= csrf_input() ?>
            <button type="submit" class="btn btn-terang"><?= t('btn_keluar') ?></button>
        </form>
<?php endif; ?>
    </div>
</header>

<main class="container">
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['tipe']) ?>"><?= t($flash['kunci'], $flash['param']) ?></div>
<?php endif; ?>
<?php
}

/** Bagian bawah halaman + konfirmasi hapus. */
function tampil_footer()
{
    ?>
</main>

<footer class="footer"><?= t('footer') ?></footer>

<script>
// Form dengan atribut data-konfirmasi menampilkan dialog konfirmasi sebelum dikirim
document.querySelectorAll('form[data-konfirmasi]').forEach(function (form) {
    form.addEventListener('submit', function (ev) {
        if (!confirm(form.getAttribute('data-konfirmasi'))) {
            ev.preventDefault();
        }
    });
});
</script>
</body>
</html>
<?php
}