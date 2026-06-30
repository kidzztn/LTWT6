<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

$productCount = (int) $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$orderCount = (int) $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$customerCount = (int) $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$categoryCount = (int) $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$revenue = (float) $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders")->fetchColumn();
$recentOrders = $pdo->query(
    "SELECT o.id, c.name AS customer_name, o.total, o.status, o.created_at
     FROM orders o
     LEFT JOIN customers c ON c.id = o.customer_id
     ORDER BY o.created_at DESC
     LIMIT 5"
)->fetchAll();
?>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="admin">

    <?php include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="main">

        <?php include __DIR__ . "/includes/topbar.php"; ?>

        <div class="content">

            <h2>Dashboard</h2>
            <p>Xin chào Admin 👋</p>

            <div class="card-box">

                <div class="card">
                    <i class="fa-solid fa-box"></i>
                    <h3><?php echo number_format($productCount); ?></h3>
                    <span>Sản phẩm</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h3><?php echo number_format($orderCount); ?></h3>
                    <span>Đơn hàng</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-users"></i>
                    <h3><?php echo number_format($customerCount); ?></h3>
                    <span>Khách hàng</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <h3><?php echo number_format($revenue, 0, ',', '.'); ?>đ</h3>
                    <span>Doanh thu</span>
                </div>

            </div>

            <div class="table-box">

                <div class="table-header">
                    <h3>Đơn hàng mới</h3>
                </div>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                    </tr>

                    <?php foreach ($recentOrders as $order): ?>
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
                            <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>

            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>