<?php
require_once 'config/init.php';

// Jika sudah login, langsung ke daftar barang
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = t('err_login_empty');
    } else {
        $stmt = $db->prepare('SELECT id, nama, password FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // cegah session fixation
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['nama'];
            header('Location: index.php');
            exit;
        }
        $error = t('err_login');
    }
}

$page_title = t('login_title');
include 'includes/header.php';
?>
<div class="card login-card">
    <h1><?= e(t('login_title')) ?></h1>
    <p class="muted"><?= e(t('login_sub')) ?></p>

    <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

    <form method="POST" novalidate>
        <div class="form-group">
            <label for="username"><?= e(t('username')) ?></label>
            <input type="text" id="username" name="username" value="<?= e($username) ?>" autofocus>
        </div>
        <div class="form-group">
            <label for="password"><?= e(t('password')) ?></label>
            <input type="password" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-block"><?= e(t('btn_login')) ?></button>
    </form>
</div>
<?php?>
