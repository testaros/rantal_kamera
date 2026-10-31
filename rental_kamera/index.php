<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/lang.php';
cek_login();

$modul = ambil_modul();
$data  = array();

// READ: ambil seluruh data sesuai modul yang dipilih
try {
    if ($modul === 'kamera') {
        $sql = "SELECT id_kamera AS id, nama_kamera, merek, jenis, harga_sewa, stok
                FROM kamera ORDER BY id_kamera ASC";
    } else {
        $sql = "SELECT p.id, p.nama_peminjam, p.no_hp, k.nama_kamera, p.tgl_pinjam, p.tgl_kembali, p.status, p.catatan
                FROM peminjaman p
                JOIN kamera k ON k.id_kamera = p.kamera_id
                ORDER BY p.tgl_pinjam DESC, p.id DESC";
    }
    $hasil = mysqli_query($koneksi, $sql);
    $data  = mysqli_fetch_all($hasil, MYSQLI_ASSOC);
} catch (mysqli_sql_exception $ex) {
    set_flash('error', 'err_db');
}

tampil_header('judul_' . $modul);
?>

<div class="head">
    <h1><?= t('judul_' . $modul) ?></h1>
    <a class="btn btn-primary" href="tambah.php?m=<?= e($modul) ?>">+ <?= t('btn_tambah') ?></a>
</div>

<p class="muted"><?= t('total', array('jumlah' => count($data))) ?></p>

<div class="tabel">
    <table>
        <thead>
            <tr>
                <th><?= t('kol_no') ?></th>
                <?php if ($modul === 'kamera'): ?>
                    <th><?= t('f_nama_kamera') ?></th>
                    <th><?= t('f_merek') ?></th>
                    <th><?= t('f_jenis') ?></th>
                    <th><?= t('f_harga_sewa') ?></th>
                    <th><?= t('f_stok') ?></th>
                <?php else: ?>
                    <th><?= t('f_nama_peminjam') ?></th>
                    <th><?= t('f_no_hp') ?></th>
                    <th><?= t('f_kamera_id') ?></th>
                    <th><?= t('f_tgl_pinjam') ?></th>
                    <th><?= t('f_tgl_kembali') ?></th>
                    <th><?= t('f_status') ?></th>
                    <th><?= t('f_catatan') ?></th>
                <?php endif; ?>
                <th><?= t('kol_aksi') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="9" class="kosong"><?= t('kosong') ?></td></tr>
            <?php endif; ?>

            <?php foreach ($data as $i => $baris): ?>
                <?php $nama_baris = ($modul === 'kamera') ? $baris['nama_kamera'] : $baris['nama_peminjam']; ?>
                <tr>
                    <td><?= $i + 1 ?></td>

                    <?php if ($modul === 'kamera'): ?>
                        <td><?= e($baris['nama_kamera']) ?></td>
                        <td><?= e($baris['merek']) ?></td>
                        <td><?= e($baris['jenis']) ?></td>
                        <td><?= e(format_harga($baris['harga_sewa'])) ?></td>
                        <td><?= (int) $baris['stok'] ?></td>
                    <?php else: ?>
                        <td><?= e($baris['nama_peminjam']) ?></td>
                        <td><?= e($baris['no_hp']) ?></td>
                        <td><?= e($baris['nama_kamera']) ?></td>
                        <td><?= e(format_tanggal($baris['tgl_pinjam'])) ?></td>
                        <td><?= e(format_tanggal($baris['tgl_kembali'])) ?></td>
                        <td><span class="badge badge-<?= e($baris['status']) ?>"><?= t('status_' . $baris['status']) ?></span></td>
                        <td><?= $baris['catatan'] === null ? '-' : e(mb_strimwidth($baris['catatan'], 0, 40, '...')) ?></td>
                    <?php endif; ?>

                    <td class="aksi">
                        <a class="btn btn-kecil" href="edit.php?m=<?= e($modul) ?>&amp;id=<?= (int) $baris['id'] ?>"><?= t('btn_ubah') ?></a>

                        <!-- DELETE: lewat POST + token CSRF + dialog konfirmasi -->
                        <form method="post" action="hapus.php" class="inline"
                              data-konfirmasi="<?= t('konfirmasi_hapus', array('nama' => $nama_baris)) ?>">
                            <?= csrf_input() ?>
                            <input type="hidden" name="m" value="<?= e($modul) ?>">
                            <input type="hidden" name="id" value="<?= (int) $baris['id'] ?>">
                            <button type="submit" class="btn btn-kecil btn-danger"><?= t('btn_hapus') ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php tampil_footer(); ?>