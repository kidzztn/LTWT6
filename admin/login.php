<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$scriptPath = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
$adminAssetBase = '/LTWT6/admin';

if ($scriptPath !== '') {
    $adminPos = strpos($scriptPath, '/admin');
    if ($adminPos !== false) {
        $adminAssetBase = substr($scriptPath, 0, $adminPos + strlen('/admin'));
    }
}
$loginError = '';

require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username !== '' && $password !== '') {
        $columns = $pdo->query("SHOW COLUMNS FROM admins")->fetchAll(PDO::FETCH_COLUMN);
        $passwordColumn = in_array('password_hash', $columns, true) ? 'password_hash' : (in_array('password', $columns, true) ? 'password' : '');

        if ($passwordColumn !== '') {
            $stmt = $pdo->prepare("SELECT id, username, " . $passwordColumn . " AS admin_password, full_name FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin) {
                $storedPassword = $admin['admin_password'];
                $isValid = $passwordColumn === 'password_hash'
                    ? password_verify($password, $storedPassword)
                    : (password_verify($password, $storedPassword) || $storedPassword === $password);

                if ($isValid) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_full_name'] = $admin['full_name'] ?? $admin['username'];

                    header('Location: ' . $adminAssetBase . '/index.php');
                    exit;
                }
            }
        }

        $loginError = 'Tên đăng nhập hoặc mật khẩu không đúng.';
    } else {
        $loginError = 'Vui lòng nhập đầy đủ thông tin.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ElectroShop Admin Login</title>

    <link rel="stylesheet" href="<?php echo $adminAssetBase; ?>/assets/css/admin.css?v=1">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body class="login-page">

<div class="login-container">

    <div class="login-left">

        <div class="overlay">

            <h1>ElectroShop</h1>

        </div>

    </div>

    <div class="login-right">

        <div class="login-box">

            <h2>Đăng nhập Admin</h2>

            <p>Đăng nhập để quản lý hệ thống</p>

            <form action="" method="POST">

                <div class="input-group">

                    <i class="fa-solid fa-user"></i>

                    <input
                        type="text"
                        name="username"
                        placeholder="Tên đăng nhập"
                        required>

                </div>

                <div class="input-group">

                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        name="password"
                        placeholder="Mật khẩu"
                        required>

                </div>

                <?php if (!empty($loginError)): ?>
                    <p style="color:#E30019; margin-bottom:20px;"><?php echo htmlspecialchars($loginError); ?></p>
                <?php endif; ?>

                <button class="btn-login">

                    <i class="fa-solid fa-right-to-bracket"></i>

                    Đăng nhập

                </button>

            </form>

            <div class="copyright">

                © 2026 ElectroShop

            </div>

        </div>

    </div>

</div>

<script src="<?php echo $adminAssetBase; ?>/assets/js/admin.js?v=1"></script>

</body>

</html>