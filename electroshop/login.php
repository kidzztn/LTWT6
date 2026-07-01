<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/customer-auth.php';

if (isCustomerLoggedIn()) {
    header('Location: index.php');
    exit;
}

include 'includes/header.php';
include 'includes/navbar.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, name, email, password_hash, password FROM customers WHERE email = ?');
    $stmt->execute([$email]);
    $customer = $stmt->fetch();

    $isValid = false;
    if ($customer) {
        $storedHash = $customer['password_hash'] ?? '';
        if ($storedHash !== '' && password_verify($password, $storedHash)) {
            $isValid = true;
        } elseif (isset($customer['password']) && $customer['password'] === $password) {
            $isValid = true;
        }
    }

    if ($isValid) {
        $_SESSION['customer_id'] = (int) $customer['id'];
        $_SESSION['customer_name'] = $customer['name'];
        $_SESSION['customer_email'] = $customer['email'];
        header('Location: index.php');
        exit;
    }

    $message = 'Email hoặc mật khẩu không đúng.';
}
?>

<main>
<section class="login-page">
    <div class="container">
        <div class="login-box">
            <div class="login-left">
                <img src="../img/banner/login-banner.png" alt="">
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
                <div class="login-footer">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></div>
            </div>
        </div>
    </div>
</section>
</main>

<?php include 'includes/footer.php'; ?>