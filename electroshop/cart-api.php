<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/cart-functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không hợp lệ.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$productId = (int) ($_POST['product_id'] ?? 0);
$quantity = max(1, (int) ($_POST['quantity'] ?? 1));

if ($productId <= 0) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => 'Sản phẩm không hợp lệ.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

addToCart($pdo, $productId, $quantity);

echo json_encode([
    'success' => true,
    'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
    'cartCount' => getCartCount(),
], JSON_UNESCAPED_UNICODE);