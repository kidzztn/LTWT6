<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/services.php';
require_once __DIR__ . '/includes/customer-auth.php';

if (isCustomerLoggedIn()) {
    header('Location: index.php');
    exit;
}

include 'includes/header.php';
include 'includes/navbar.php';

$message = '';
$facebookLoginUrl = isFacebookLoginConfigured() ? buildFacebookLoginUrl() : 'facebook-login.php';

if (isset($_GET['message'])) {
    $message = trim((string) $_GET['message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM customers WHERE email = ?');
    $stmt->execute([$email]);
    $customer = $stmt->fetch();

    $isValid = false;
    if ($customer) {
        $storedHash = $customer['password_hash'] ?? '';
        if ($storedHash !== '' && password_verify($password, $storedHash)) {
            $isValid = true;
        }
    }

    if ($isValid) {
        loginCustomerSession($customer);
        header('Location: index.php');
        exit;
    }

    if ($customer && ($customer['password_hash'] ?? '') === '' && (($customer['auth_provider'] ?? '') === 'facebook')) {
        $message = 'Tài khoản này đang đăng nhập bằng Facebook. Vui lòng chọn nút Facebook.';
    } else {
        $message = 'Email hoặc mật khẩu không đúng.';
    }
}
?>

<main>
<section class="login-page">
    <div class="container">
        <div class="login-box">
            <div class="login-left">
                <img src="../img/uploads/14.webp" alt="ElectroShop login image">
            </div>
            <div class="login-right">
                <h2>Đăng nhập</h2>
                <p>Chào mừng bạn quay trở lại ElectroShop.</p>
                <?php if ($message !== ''): ?>
                    <div class="alert alert-danger" style="margin-bottom:15px;"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="login" value="1">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Nhập email" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>
                    <button class="btn-login" type="submit">Đăng nhập</button>
                </form>
                <div class="auth-divider"><span>hoặc</span></div>
                <a class="social-auth-btn facebook" href="<?php echo htmlspecialchars($facebookLoginUrl); ?>">
                    <i class="fa-brands fa-facebook-f"></i>
                    Tiếp tục với Facebook
                </a>
                <div class="login-footer">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></div>
            </div>
        </div>
    </div>
</section>
</main>

<?php include 'includes/footer.php'; ?>