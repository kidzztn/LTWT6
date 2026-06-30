<?php
// ===================================================
// BẢO VỆ TRANG ADMIN - include file này ở đầu mỗi trang
// cần đăng nhập mới xem được
// ===================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$scriptPath = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
$adminBasePath = '/LTWT6/admin';

if ($scriptPath !== '') {
    $adminPos = strpos($scriptPath, '/admin');
    if ($adminPos !== false) {
        $adminBasePath = substr($scriptPath, 0, $adminPos + strlen('/admin'));
    }
}

if (empty($_SESSION['admin_id'])) {
    header('Location: ' . $adminBasePath . '/login.php');
    exit;
}