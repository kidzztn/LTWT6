<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/customer-auth.php';
include 'includes/header.php';
include 'includes/navbar.php';

function buildVietQrUrl(float $amount, string $addInfo): string
{
    $bankBin = '970422';
    $accountNo = '19037049999999';
    $accountName = 'ELECTROSHOP';

    return 'https://img.vietqr.io/image/'
        . $bankBin . '-' . $accountNo . '-compact2.png'
        . '?amount=' . (int) round($amount)
        . '&addInfo=' . rawurlencode($addInfo)
        . '&accountName=' . rawurlencode($accountName);
}

if (!isCustomerLoggedIn()) {
    header('Location: login.php');
    exit;
}

$customer = getCurrentCustomer();
$orderId = (int) ($_GET['id'] ?? 0);
$order = null;
$items = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $stmt = $pdo->prepare('UPDATE orders SET status = "cancel" WHERE id = ? AND customer_id = ? AND status = "pending"');
    $stmt->execute([$orderId, $customer['id']]);
    if ($stmt->rowCount() > 0) {
        $message = 'Đã hủy đơn hàng thành công.';
    } else {
        $message = 'Không thể hủy đơn hàng này.';
    }
}

if ($orderId > 0) {
    $stmt = $pdo->prepare('SELECT id, total, status, payment_method, payment_status, payment_note, created_at FROM orders WHERE id = ? AND customer_id = ?');
    $stmt->execute([$orderId, $customer['id']]);
    $order = $stmt->fetch();

    if ($order) {
        $stmt = $pdo->prepare('SELECT product_name, price, quantity FROM order_items WHERE order_id = ?');
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll();
    }
}

if (!$order) {
    header('Location: orders.php');
    exit;
}
?>

<main>
    <section class="cart-page">
        <div class="container">
            <h2>Chi tiết đơn hàng #<?php echo (int) $order['id']; ?></h2>
            <?php if ($message !== ''): ?>
                <div class="alert alert-info" style="margin-bottom: 20px;"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <div class="alert alert-info" style="margin-bottom: 20px;">
                Trạng thái: <strong><?php echo htmlspecialchars(match ($order['status']) {
                    'pending' => 'Đang xử lý',
                    'success' => 'Đã thanh toán',
                    'cancel' => 'Đã hủy',
                    default => 'Không rõ',
                }); ?></strong>
            </div>

            <div class="alert alert-info" style="margin-bottom: 20px;">
                Thanh toan:
                <strong>
                    <?php
                    $paymentMethodLabel = ($order['payment_method'] ?? 'cash') === 'transfer' ? 'Chuyen khoan' : 'COD';
                    $paymentStatusLabel = match ($order['payment_status'] ?? 'unpaid') {
                        'paid' => 'Da thanh toan',
                        'refunded' => 'Da hoan tien',
                        default => 'Chua thanh toan',
                    };
                    echo htmlspecialchars($paymentMethodLabel . ' - ' . $paymentStatusLabel);
                    ?>
                </strong>
            </div>

            <?php if (!empty($order['payment_note'])): ?>
                <div class="alert alert-info" style="margin-bottom: 20px;">
                    Ghi chu thanh toan: <?php echo htmlspecialchars((string) $order['payment_note']); ?>
                </div>
            <?php endif; ?>

            <?php if (($order['payment_method'] ?? 'cash') === 'transfer' && ($order['payment_status'] ?? 'unpaid') === 'unpaid'): ?>
                <?php $transferCode = 'ES' . date('Ymd', strtotime((string) $order['created_at'])) . '-' . (int) $order['id']; ?>
                <div class="alert alert-info" style="margin-bottom: 20px;">
                    <p><strong>Don hang chua thanh toan. Vui long chuyen khoan theo thong tin sau:</strong></p>
                    <p>Ngan hang: MB Bank</p>
                    <p>So tai khoan: 19037049999999</p>
                    <p>Chu tai khoan: ELECTROSHOP</p>
                    <p>So tien: <?php echo number_format((float) $order['total'], 0, ',', '.'); ?> VND</p>
                    <p>Noi dung CK: <strong><?php echo htmlspecialchars($transferCode); ?></strong></p>
                    <div style="margin-top: 10px;">
                        <img src="<?php echo htmlspecialchars(buildVietQrUrl((float) $order['total'], $transferCode)); ?>" alt="VietQR" style="max-width: 220px; border: 1px solid #eee; border-radius: 8px;">
                    </div>
                </div>
            <?php endif; ?>

            <div class="table-box" style="margin-bottom: 20px;">
                <h3>Sản phẩm trong đơn</h3>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td><?php echo number_format((float) $item['price'], 0, ',', '.'); ?>₫</td>
                                <td><?php echo (int) $item['quantity']; ?></td>
                                <td><?php echo number_format((float) $item['price'] * (int) $item['quantity'], 0, ',', '.'); ?>₫</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p><strong>Tổng tiền:</strong> <?php echo number_format((float) $order['total'], 0, ',', '.'); ?>₫</p>
            <p><strong>Ngày đặt:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
            <?php if ($order['status'] === 'pending'): ?>
                <form method="post" style="margin-top: 15px;">
                    <input type="hidden" name="cancel_order" value="1">
                    <button type="submit" class="btn-small danger">Hủy đơn hàng</button>
                </form>
            <?php endif; ?>
            <a href="orders.php" class="btn-primary" style="margin-top: 15px; display: inline-block;">Quay lại lịch sử đơn hàng</a>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
