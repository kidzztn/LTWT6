<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/services.php';

$payload = json_decode(file_get_contents('php://input'), true) ?: [];
$resultCode = (int) ($payload['resultCode'] ?? -1);
$orderId = extractOrderIdFromMoMoExtraData((string) ($payload['extraData'] ?? ''));

if ($orderId > 0) {
    $stmt = $pdo->prepare('UPDATE orders SET payment_status = ?, status = ?, payment_gateway_reference = ?, payment_gateway_payload = ? WHERE id = ?');
    $stmt->execute([
        $resultCode === 0 ? 'paid' : 'unpaid',
        $resultCode === 0 ? 'success' : 'pending',
        (string) ($payload['orderId'] ?? ''),
        json_encode($payload, JSON_UNESCAPED_UNICODE),
        $orderId,
    ]);
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['resultCode' => 0, 'message' => 'Received'], JSON_UNESCAPED_UNICODE);