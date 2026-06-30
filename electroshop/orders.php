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
$stmt = $pdo->prepare('SELECT id, total, status, created_at FROM orders WHERE customer_id = ? ORDER BY id DESC');
$stmt->execute([$customer['id']]);
$orders = $stmt->fetchAll();

function getOrderStatusLabel(string $status): string
{
    return match ($status) {
        'pending' => 'Đang xử lý',
        'success' => 'Đã thanh toán',
        'cancel' => 'Đã hủy',
        default => 'Không rõ',
    };
}
?>

<main>
    <section class="cart-page">
        <div class="container">
            <h2>Lịch sử đơn hàng</h2>
            <?php if (empty($orders)): ?>
                <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
            <?php else: ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><a href="order-detail.php?id=<?php echo (int) $order['id']; ?>">#<?php echo (int) $order['id']; ?></a></td>
                                <td><?php echo number_format((float) $order['total'], 0, ',', '.'); ?>₫</td>
                                <td><?php echo htmlspecialchars(getOrderStatusLabel($order['status'])); ?></td>
                                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
