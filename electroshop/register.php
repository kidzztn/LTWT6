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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || $phone === '' || $email === '' || $password === '') {
        $message = 'Vui lòng nhập đầy đủ thông tin.';
    } elseif ($password !== $confirmPassword) {
        $message = 'Mật khẩu xác nhận không khớp.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id FROM customers WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $message = 'Email này đã được sử dụng.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO customers (name, email, phone, address, password_hash) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$name, $email, $phone, '', $hashedPassword]);
                $customerId = (int) $pdo->lastInsertId();
                loginCustomerSession([
                    'id' => $customerId,
                    'name' => $name,
                    'email' => $email,
                    'auth_provider' => 'email',
                    'avatar_url' => '',
                ]);
                header('Location: index.php');
                exit;
            }
        } catch (Exception $e) {
            $message = 'Đăng ký thất bại: ' . $e->getMessage();
        }
    }
}
?>

<main>
<section class="register-page">
<div class="container">
<div class="register-box">
<h2>Tạo tài khoản ElectroShop</h2>
<?php if ($message !== ''): ?>
    <div class="alert alert-info" style="margin-bottom:15px;"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<form method="POST">
<input type="hidden" name="register" value="1">
<div class="row">
<div class="col">
<label>Họ tên</label>
<input type="text" name="name" required>
</div>
<div class="col">
<label>Số điện thoại</label>
<input type="text" name="phone" required>
</div>
</div>
<label>Email</label>
<input type="email" name="email" required>
<label>Mật khẩu</label>
<input type="password" name="password" required>
<label>Nhập lại mật khẩu</label>
<input type="password" name="confirm_password" required>
<button class="btn-register" type="submit">Đăng ký</button>
</form>
<div class="auth-divider"><span>hoặc</span></div>
<a class="social-auth-btn facebook" href="<?php echo htmlspecialchars($facebookLoginUrl); ?>">
    <i class="fa-brands fa-facebook-f"></i>
    Đăng ký nhanh với Facebook
</a>
<div class="register-footer">Đã có tài khoản? <a href="login.php">Đăng nhập</a></div>
</div>
</div>
</section>
</main>

<?php include 'includes/footer.php'; ?>