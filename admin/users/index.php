<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM customers WHERE id = ?');
            $stmt->execute([$id]);
            $successMessage = 'Xóa khách hàng thành công.';
        }
    }
}

$customers = $pdo->query(
    "SELECT c.id, c.name, c.email, c.phone, c.address,
            CASE WHEN c.password_hash IS NOT NULL AND c.password_hash <> '' THEN 1 ELSE 0 END AS is_registered,
            COUNT(o.id) AS order_count,
            COALESCE(SUM(o.total), 0) AS total_spent,
            MAX(o.created_at) AS last_order_at
     FROM customers c
     LEFT JOIN orders o ON o.customer_id = c.id
     GROUP BY c.id, c.name, c.email, c.phone, c.address, c.password_hash
     ORDER BY c.id DESC"
)->fetchAll();

$customerCount = count($customers);
$registeredCount = 0;
$customerOrderCount = 0;

foreach ($customers as $customer) {
    if ((int) ($customer['is_registered'] ?? 0) === 1) {
        $registeredCount++;
    }

    $customerOrderCount += (int) ($customer['order_count'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khách hàng</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=7">
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
                    <h2>Quản lý khách hàng</h2>
                    <p>Danh sách người dùng đăng ký trong hệ thống.</p>
                </div>
            </div>

            <div class="stats-grid" style="margin-bottom: 20px;">
                <div class="card">
                    <h3><?php echo $customerCount; ?></h3>
                    <p>Tổng khách hàng</p>
                </div>
                <div class="card">
                    <h3><?php echo $registeredCount; ?></h3>
                    <p>Tài khoản đã đăng ký</p>
                </div>
                <div class="card">
                    <h3><?php echo $customerOrderCount; ?></h3>
                    <p>Tổng đơn hàng gắn khách</p>
                </div>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>

            <div class="table-box">
                <?php if (empty($customers)): ?>
                    <div class="empty-state">
                        <h3>Chưa có khách hàng nào</h3>
                        <p>Người dùng đăng ký hoặc đặt đơn sẽ xuất hiện tại đây.</p>
                    </div>
                <?php else: ?>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Loại tài khoản</th>
                            <th>Số đơn</th>
                            <th>Tổng mua</th>
                            <th>Đơn gần nhất</th>
                            <th>Hành động</th>
                        </tr>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td>#<?php echo (int) $customer['id']; ?></td>
                                <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['email'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($customer['phone'] ?? '-'); ?></td>
                                <td>
                                    <?php if ((int) ($customer['is_registered'] ?? 0) === 1): ?>
                                        <span class="status success">Đã đăng ký</span>
                                    <?php else: ?>
                                        <span class="status pending">Khách mua nhanh</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo (int) ($customer['order_count'] ?? 0); ?></td>
                                <td><?php echo number_format((float) ($customer['total_spent'] ?? 0), 0, ',', '.'); ?>đ</td>
                                <td><?php echo htmlspecialchars($customer['last_order_at'] ?? '-'); ?></td>
                                <td>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo (int) $customer['id']; ?>">
                                        <button type="submit" class="btn-small danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
