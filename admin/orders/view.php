<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';

$id = (int) ($_GET['id'] ?? 0);
$order = null;
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $status = $_POST['status'] ?? 'pending';
    if (in_array($status, ['pending', 'success', 'cancel'], true)) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
        $successMessage = 'Cập nhật trạng thái đơn hàng thành công.';
    } else {
        $errorMessage = 'Trạng thái không hợp lệ.';
    }
}

if ($id > 0) {
    $stmt = $pdo->prepare(
        "SELECT o.id, o.total, o.status, o.created_at, c.name AS customer_name, c.email, c.phone, c.address
         FROM orders o
         LEFT JOIN customers c ON c.id = o.customer_id
         WHERE o.id = ?"
    );
    $stmt->execute([$id]);
    $order = $stmt->fetch();
}

if (!$order) {
    header('Location: index.php');
    exit;
}

$items = $pdo->prepare(
    "SELECT product_name, price, quantity FROM order_items WHERE order_id = ?"
);
$items->execute([$id]);
$items = $items->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
<div class="admin">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="content">
            <h2>Chi tiết đơn hàng #<?php echo (int) $order['id']; ?></h2>
            <p>Thông tin khách hàng và sản phẩm trong đơn hàng.</p>

            <?php if (!empty($successMessage)): ?>
                <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>

            <div class="table-box" style="margin-bottom:20px;">
                <h3>Thông tin khách hàng</h3>
                <p><strong>Tên:</strong> <?php echo htmlspecialchars($order['customer_name'] ?? 'Khách vãng lai'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email'] ?? '-'); ?></p>
                <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['phone'] ?? '-'); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address'] ?? '-'); ?></p>
                <p><strong>Tổng tiền:</strong> <?php echo number_format((float) $order['total'], 0, ',', '.'); ?>đ</p>
                <p><strong>Ngày tạo:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>

                <form method="post" style="margin-top:15px;">
                    <input type="hidden" name="update_status" value="1">
                    <label for="status" style="display:block;margin-bottom:6px;"><strong>Trạng thái:</strong></label>
                    <select name="status" id="status">
                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Đang xử lý</option>
                        <option value="success" <?php echo $order['status'] === 'success' ? 'selected' : ''; ?>>Đã thanh toán</option>
                        <option value="cancel" <?php echo $order['status'] === 'cancel' ? 'selected' : ''; ?>>Đã hủy</option>
                    </select>
                    <button type="submit" class="btn-primary" style="margin-left:10px;">Cập nhật</button>
                </form>
            </div>

            <div class="table-box">
                <h3>Sản phẩm trong đơn hàng</h3>
                <table>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo number_format((float) $item['price'], 0, ',', '.'); ?>đ</td>
                            <td><?php echo (int) $item['quantity']; ?></td>
                            <td><?php echo number_format((float) $item['price'] * (int) $item['quantity'], 0, ',', '.'); ?>đ</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
