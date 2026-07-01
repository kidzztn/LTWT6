<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/customer-auth.php';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isCustomerLoggedIn()) {
    header('Location: login.php');
    exit;
}

$customer = getCurrentCustomer();
$profile = [];
$message = '';
$errorMessage = '';

$stmt = $pdo->prepare('SELECT id, name, email, phone, address FROM customers WHERE id = ?');
$stmt->execute([$customer['id']]);
$profile = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '' || $email === '') {
        $errorMessage = 'Vui lòng nhập đầy đủ họ tên và email.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM customers WHERE email = ? AND id != ?');
        $stmt->execute([$email, $customer['id']]);
        if ($stmt->fetch()) {
            $errorMessage = 'Email này đã được sử dụng bởi tài khoản khác.';
        } else {
            $stmt = $pdo->prepare('UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?');
            $stmt->execute([$name, $email, $phone, $address, $customer['id']]);
            $_SESSION['customer_name'] = $name;
            $_SESSION['customer_email'] = $email;
            $message = 'Cập nhật thông tin tài khoản thành công.';
            $profile['name'] = $name;
            $profile['email'] = $email;
            $profile['phone'] = $phone;
            $profile['address'] = $address;
        }
    }
}
?>

<main>
<section class="profile-page">
<div class="container">
<div class="profile-layout">
<div class="profile-menu">
<img src="../img/uploads/1.webp" alt="Avatar">
<h3><?php echo htmlspecialchars($profile['name'] ?? $customer['name']); ?></h3>
<ul>
<li><a href="orders.php">Đơn hàng</a></li>
<li><a href="logout.php">Đăng xuất</a></li>
</ul>
</div>
<div class="profile-content">
<h2>Thông tin tài khoản</h2>
<?php if ($message !== ''): ?>
    <div class="alert alert-success" style="margin-bottom:15px;"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<?php if ($errorMessage !== ''): ?>
    <div class="alert alert-danger" style="margin-bottom:15px;"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>
<form method="post">
    <input type="hidden" name="update_profile" value="1">
    <label>Họ tên</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($profile['name'] ?? $customer['name']); ?>" required>
    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? $customer['email']); ?>" required>
    <label>Số điện thoại</label>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>">
    <label>Địa chỉ</label>
    <input type="text" name="address" value="<?php echo htmlspecialchars($profile['address'] ?? ''); ?>">
    <button type="submit" class="btn-register" style="margin-top:20px;">Cập nhật thông tin</button>
</form>
</div>
</div>
</div>
</section>
</main>

<?php
include 'includes/footer.php';
?>