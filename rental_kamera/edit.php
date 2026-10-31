<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/lang.php';
cek_login();

$modul = ambil_modul();

// Identitas data yang diubah selalu diambil dari URL (?id=...), bukan dari isi form
$id = (isset($_GET['id']) && is_string($_GET['id']) && ctype_digit($_GET['id'])) ? (int) $_GET['id'] : 0;

if ($id < 1) {
    set_flash('error', 'err_tidak_ada');
    header('Location: index.php?m=' . $modul);
    exit;
}

// Ambil data yang akan diubah
try {
    if ($modul === 'kamera') {
        $sql = "SELECT nama_kamera, merek, jenis, harga_sewa, stok FROM kamera WHERE id_kamera = ?";
    } else {
        $sql = "SELECT nama_peminjam, no_hp, kamera_id, tgl_pinjam, tgl_kembali, status, catatan
                FROM peminjaman WHERE id = ?";
    }
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
    $ada   = mysqli_fetch_assoc($hasil);
    mysqli_stmt_close($stmt);
} catch (mysqli_sql_exception $ex) {
    set_flash('error', 'err_db');
    header('Location: index.php?m=' . $modul);
    exit;
}

if (!$ada) {
    set_flash('error', 'err_tidak_ada');
    header('Location: index.php?m=' . $modul);
    exit;
}

// Jadikan semua nilai string (NULL menjadi '') agar konsisten dengan data dari form
$data = array();
foreach ($ada as $kolom => $nilai) {
    $data[$kolom] = (string) $nilai;
}
$error = array();

// Daftar kamera untuk dropdown pada modul peminjaman
$daftar_kamera = array();
if ($modul === 'peminjaman') {
    try {
        $hasil         = mysqli_query($koneksi, "SELECT id_kamera, nama_kamera, merek FROM kamera ORDER BY nama_kamera ASC");
        $daftar_kamera = mysqli_fetch_all($hasil, MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $ex) {
        set_flash('error', 'err_db');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $error['form'] = array('err_csrf', array());
    } else {
        // 1. Sanitasi input
        foreach ($data as $kolom => $nilai) {
            $data[$kolom] = ambil_post($kolom, $kolom === 'catatan');
        }

        // 2. Validasi
        $error = ($modul === 'kamera') ? validasi_kamera($data) : validasi_peminjaman($data);

        // 3. Perbarui database jika tidak ada error
        if (empty($error)) {
            try {
                if ($modul === 'kamera') {
                    $harga = (int) $data['harga_sewa'];
                    $stok  = (int) $data['stok'];
                    $nama  = $data['nama_kamera'];

                    $stmt = mysqli_prepare($koneksi,
                        "UPDATE kamera SET nama_kamera = ?, merek = ?, jenis = ?, harga_sewa = ?, stok = ? WHERE id_kamera = ?");
                    mysqli_stmt_bind_param($stmt, 'sssiii',
                        $data['nama_kamera'], $data['merek'], $data['jenis'], $harga, $stok, $id);
                } else {
                    $kamera_id = (int) $data['kamera_id'];
                    $catatan   = ($data['catatan'] === '') ? null : $data['catatan'];
                    $nama      = $data['nama_peminjam'];

                    $stmt = mysqli_prepare($koneksi,
                        "UPDATE peminjaman SET nama_peminjam = ?, no_hp = ?, kamera_id = ?, tgl_pinjam = ?,
                                tgl_kembali = ?, status = ?, catatan = ?
                         WHERE id = ?");
                    mysqli_stmt_bind_param($stmt, 'ssissssi',
                        $data['nama_peminjam'], $data['no_hp'], $kamera_id,
                        $data['tgl_pinjam'], $data['tgl_kembali'], $data['status'], $catatan, $id);
                }

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                set_flash('sukses', 'msg_ubah', array('nama' => $nama));
                header('Location: index.php?m=' . $modul);
                exit;
            } catch (mysqli_sql_exception $ex) {
                if ($ex->getCode() == 1452) { // foreign key: kamera tidak ada
                    $error['kamera_id'] = array('err_kamera_tidak_ada', array());
                } else {
                    $error['form'] = array('err_db', array());
                }
            }
        }
    }
}

tampil_header('judul_edit_' . $modul);
?>

<div class="head">
    <h1><?= t('judul_edit_' . $modul) ?></h1>
</div>

<?php if (isset($error['form'])): ?>
    <div class="alert alert-error"><?= t($error['form'][0], $error['form'][1]) ?></div>
<?php endif; ?>

<form method="post" action="edit.php?m=<?= e($modul) ?>&amp;id=<?= (int) $id ?>" class="card" novalidate>
    <?= csrf_input() ?>

    <?php if ($modul === 'kamera'): ?>
        <?= field_input('nama_kamera', $data, $error, 100) ?>
        <?= field_input('merek', $data, $error, 50) ?>
        <?= field_select('jenis', $data, $error, opsi_jenis()) ?>
        <?= field_input('harga_sewa', $data, $error, 9) ?>
        <?= field_input('stok', $data, $error, 4) ?>
    <?php else: ?>
        <?= field_input('nama_peminjam', $data, $error, 100) ?>
        <?= field_input('no_hp', $data, $error, 20) ?>
        <?= field_select('kamera_id', $data, $error, opsi_kamera($daftar_kamera), 'pilih_kamera') ?>
        <?= field_input('tgl_pinjam', $data, $error, 10, 'date') ?>
        <?= field_input('tgl_kembali', $data, $error, 10, 'date') ?>
        <?= field_select('status', $data, $error, opsi_status()) ?>
        <?= field_textarea('catatan', $data, $error, 500) ?>
    <?php endif; ?>

    <div class="aksi-form">
        <button type="submit" class="btn btn-primary"><?= t('btn_simpan_ubah') ?></button>
        <a href="index.php?m=<?= e($modul) ?>" class="btn btn-terang"><?= t('btn_batal') ?></a>
    </div>
</form>

<?php tampil_footer(); ?>