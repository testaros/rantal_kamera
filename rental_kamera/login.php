<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/lang.php';

// Jika sudah login, langsung ke halaman utama
if (sudah_login()) {
    header('Location: index.php');
    exit;
}

$username    = '';
$kunci_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $kunci_error = 'err_csrf';
    } else {
        $username = ambil_post('username');
        // Password tidak disanitasi; hanya dibandingkan dengan hash di database
        $password = (isset($_POST['password']) && is_string($_POST['password'])) ? $_POST['password'] : '';

        if ($username === '' || $password === '') {
            $kunci_error = 'err_login_kosong';
        } else {
            try {
                $stmt = mysqli_prepare($koneksi, "SELECT id, username, password FROM users WHERE username = ?");
                mysqli_stmt_bind_param($stmt, 's', $username);
                mysqli_stmt_execute($stmt);
                $hasil = mysqli_stmt_get_result($stmt);
                $user  = mysqli_fetch_assoc($hasil);
                mysqli_stmt_close($stmt);

                if ($user && password_verify($password, $user['password'])) {
                    // Cegah session fixation, lalu simpan identitas di session
                    session_regenerate_id(true);
                    unset($_SESSION['csrf']);
                    $_SESSION['user_id']  = (int) $user['id'];
                    $_SESSION['username'] = $user['username'];

                    header('Location: index.php');
                    exit;
                } else {
                    $kunci_error = 'err_login_gagal';
                }
            } catch (mysqli_sql_exception $ex) {
                $kunci_error = 'err_db';
            }
        }
    }
}

tampil_header('judul_login');
?>

<div class="login">
    <h1><?= t('login_judul') ?></h1>
    <p class="muted"><?= t('login_sub') ?></p>

    <?php if ($kunci_error !== ''): ?>
        <div class="alert alert-error"><?= t($kunci_error) ?></div>
    <?php endif; ?>

    <form method="post" action="login.php" class="card" novalidate>
        <?= csrf_input() ?>

        <div class="form-group">
            <label for="username"><?= t('f_username') ?></label>
            <input type="text" id="username" name="username" value="<?= e($username) ?>"
                   maxlength="50" autocomplete="username" autofocus>
        </div>

        <div class="form-group">
            <label for="password"><?= t('f_password') ?></label>
            <input type="password" id="password" name="password" autocomplete="current-password">
        </div>

        <button type="submit" class="btn btn-primary btn-block"><?= t('btn_masuk') ?></button>
    </form>
</div>

<?php tampil_footer(); ?>