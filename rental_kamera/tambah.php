<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/lang.php';
cek_login();

$modul = ambil_modul();
$error = array();

// Nilai awal form
if ($modul === 'kamera') {
    $data = array('nama_kamera' => '', 'merek' => '', 'jenis' => 'DSLR', 'harga_sewa' => '', 'stok' => '1');
} else {
    $data = array(
        'nama_peminjam' => '',
        'no_hp'         => '',
        'kamera_id'     => '',
        'tgl_pinjam'    => date('Y-m-d'),
        'tgl_kembali'   => date('Y-m-d'),
        'status'        => 'Dipinjam',
        'catatan'       => '',
    );
}

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

        // 2. Validasi kelengkapan & format
        $error = ($modul === 'kamera') ? validasi_kamera($data) : validasi_peminjaman($data);

        // 3. Simpan ke database jika tidak ada error
        if (empty($error)) {
            try {
                if ($modul === 'kamera') {
                    $harga = (int) $data['harga_sewa'];
                    $stok  = (int) $data['stok'];
                    $nama  = $data['nama_kamera'];

                    $stmt = mysqli_prepare($koneksi,
                        "INSERT INTO kamera (nama_kamera, merek, jenis, harga_sewa, stok) VALUES (?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, 'sssii',
                        $data['nama_kamera'], $data['merek'], $data['jenis'], $harga, $stok);
                } else {
                    $kamera_id = (int) $data['kamera_id'];
                    $catatan   = ($data['catatan'] === '') ? null : $data['catatan'];
                    $nama      = $data['nama_peminjam'];

                    $stmt = mysqli_prepare($koneksi,
                        "INSERT INTO peminjaman (nama_peminjam, no_hp, kamera_id, tgl_pinjam, tgl_kembali, status, catatan)
                         VALUES (?, ?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, 'ssissss',
                        $data['nama_peminjam'], $data['no_hp'], $kamera_id,
                        $data['tgl_pinjam'], $data['tgl_kembali'], $data['status'], $catatan);
                }

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                set_flash('sukses', 'msg_tambah', array('nama' => $nama));
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

tampil_header('judul_tambah_' . $modul);
?>

<div class="head">
    <h1><?= t('judul_tambah_' . $modul) ?></h1>
</div>

<?php if (isset($error['form'])): ?>
    <div class="alert alert-error"><?= t($error['form'][0], $error['form'][1]) ?></div>
<?php endif; ?>

<form method="post" action="tambah.php?m=<?= e($modul) ?>" class="card" novalidate>
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
        <button type="submit" class="btn btn-primary"><?= t('btn_simpan') ?></button>
        <a href="index.php?m=<?= e($modul) ?>" class="btn btn-terang"><?= t('btn_batal') ?></a>
    </div>
</form>

<?php tampil_footer(); ?>