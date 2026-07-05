<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM orders WHERE id = ?');
            $stmt->execute([$id]);
            $successMessage = 'Xóa đơn hàng thành công.';
        }
    }
}

$orders = $pdo->query(
    "SELECT o.id, c.name AS customer_name, o.total, o.status, o.payment_method, o.payment_status, o.created_at
     FROM orders o
     LEFT JOIN customers c ON c.id = o.customer_id
     ORDER BY o.id DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
<div class="admin">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="content">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <div>
                    <h2>Quản lý đơn hàng</h2>
                    <p>Xem và quản lý đơn hàng khách mua.</p>
                </div>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>

            <div class="table-box">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thanh toan</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo (int) $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Khách vãng lai'); ?></td>
                            <td><?php echo number_format((float) $order['total'], 0, ',', '.'); ?>đ</td>
                            <td>
                                <?php
                                $statusClass = 'pending';
                                $statusText = 'Đang xử lý';
                                if ($order['status'] === 'success') {
                                    $statusClass = 'success';
                                    $statusText = 'Đã thanh toán';
                                } elseif ($order['status'] === 'cancel') {
                                    $statusClass = 'cancel';
                                    $statusText = 'Đã hủy';
                                }
                                ?>
                                <span class="status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td>
                                <?php
                                $paymentMethodText = ($order['payment_method'] ?? 'cash') === 'transfer' ? 'Chuyen khoan' : 'COD';
                                $paymentStatusText = match ($order['payment_status'] ?? 'unpaid') {
                                    'paid' => 'Da thanh toan',
                                    'refunded' => 'Da hoan tien',
                                    default => 'Chua thanh toan',
                                };
                                ?>
                                <span><?php echo htmlspecialchars($paymentMethodText . ' - ' . $paymentStatusText); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                            <td>
                                <a href="view.php?id=<?php echo (int) $order['id']; ?>" class="btn-small">Xem</a>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo (int) $order['id']; ?>">
                                    <button type="submit" class="btn-small danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
