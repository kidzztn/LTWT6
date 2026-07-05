<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/services.php';
require_once __DIR__ . '/includes/customer-auth.php';

if (!isFacebookLoginConfigured()) {
    header('Location: login.php?message=' . urlencode('Facebook Login chưa được cấu hình App ID/App Secret.'));
    exit;
}

$state = (string) ($_GET['state'] ?? '');
$code = (string) ($_GET['code'] ?? '');
$expectedState = (string) ($_SESSION['fb_oauth_state'] ?? '');
unset($_SESSION['fb_oauth_state']);

if ($state === '' || $expectedState === '' || !hash_equals($expectedState, $state)) {
    header('Location: login.php?message=' . urlencode('Phiên đăng nhập Facebook không hợp lệ.'));
    exit;
}

if ($code === '') {
    header('Location: login.php?message=' . urlencode('Không nhận được mã xác thực từ Facebook.'));
    exit;
}

try {
    $facebookUser = fetchFacebookUserFromCode($code);
    $facebookId = (string) ($facebookUser['id'] ?? '');
    $facebookName = trim((string) ($facebookUser['name'] ?? 'Facebook User'));
    $facebookEmail = trim((string) ($facebookUser['email'] ?? ''));
    $avatarUrl = (string) ($facebookUser['picture']['data']['url'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM customers WHERE facebook_id = ? LIMIT 1');
    $stmt->execute([$facebookId]);
    $customer = $stmt->fetch();

    if (!$customer && $facebookEmail !== '') {
        $stmt = $pdo->prepare('SELECT * FROM customers WHERE email = ? LIMIT 1');
        $stmt->execute([$facebookEmail]);
        $customer = $stmt->fetch();
    }

    if ($customer) {
        $stmt = $pdo->prepare('UPDATE customers SET name = ?, email = ?, facebook_id = ?, auth_provider = ?, avatar_url = ? WHERE id = ?');
        $stmt->execute([
            $facebookName,
            $facebookEmail !== '' ? $facebookEmail : ($customer['email'] ?? null),
            $facebookId,
            'facebook',
            $avatarUrl !== '' ? $avatarUrl : ($customer['avatar_url'] ?? null),
            (int) $customer['id'],
        ]);

        $stmt = $pdo->prepare('SELECT * FROM customers WHERE id = ? LIMIT 1');
        $stmt->execute([(int) $customer['id']]);
        $customer = $stmt->fetch();
    } else {
        $stmt = $pdo->prepare('INSERT INTO customers (name, email, phone, address, password_hash, auth_provider, facebook_id, avatar_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $facebookName,
            $facebookEmail !== '' ? $facebookEmail : null,
            '',
            '',
            null,
            'facebook',
            $facebookId,
            $avatarUrl,
        ]);

        $stmt = $pdo->prepare('SELECT * FROM customers WHERE id = ? LIMIT 1');
        $stmt->execute([(int) $pdo->lastInsertId()]);
        $customer = $stmt->fetch();
    }

    loginCustomerSession($customer ?: []);
    header('Location: index.php');
    exit;
} catch (Throwable $e) {
    header('Location: login.php?message=' . urlencode('Đăng nhập Facebook thất bại: ' . $e->getMessage()));
    exit;
}