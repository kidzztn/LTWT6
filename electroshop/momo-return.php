<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/services.php';

$resultCode = (int) ($_GET['resultCode'] ?? -1);
$orderId = extractOrderIdFromMoMoExtraData((string) ($_GET['extraData'] ?? ''));
$gatewayReference = (string) ($_GET['orderId'] ?? '');
$payload = json_encode($_GET, JSON_UNESCAPED_UNICODE);

if ($orderId > 0) {
    $stmt = $pdo->prepare('UPDATE orders SET payment_status = ?, status = ?, payment_gateway_reference = ?, payment_gateway_payload = ? WHERE id = ?');
    $stmt->execute([
        $resultCode === 0 ? 'paid' : 'unpaid',
        $resultCode === 0 ? 'success' : 'pending',
        $gatewayReference !== '' ? $gatewayReference : null,
        $payload,
        $orderId,
    ]);
}

$redirectTarget = $orderId > 0 ? 'order-detail.php?id=' . $orderId : 'orders.php';
$separator = strpos($redirectTarget, '?') === false ? '?' : '&';
header('Location: ' . $redirectTarget . $separator . 'message=' . urlencode($resultCode === 0 ? 'Thanh toán MoMo thành công.' : 'Thanh toán MoMo chưa hoàn tất.'));
exit;