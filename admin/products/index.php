<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $successMessage = 'Xóa sản phẩm thành công.';
        }
    }
}

$products = $pdo->query(
    "SELECT p.id, p.name, p.price, p.stock, p.slug, c.name AS category_name
     FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     ORDER BY p.id DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
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
                    <h2>Quản lý sản phẩm</h2>
                    <p>Thêm, sửa và xóa sản phẩm trong cửa hàng.</p>
                </div>
                <a href="create.php" class="btn-primary">+ Thêm sản phẩm</a>
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
                        <th>Tên</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Slug</th>
                        <th>Hành động</th>
                    </tr>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>#<?php echo (int) $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category_name'] ?? '-'); ?></td>
                            <td><?php echo number_format((float) $product['price'], 0, ',', '.'); ?>đ</td>
                            <td><?php echo (int) $product['stock']; ?></td>
                            <td><?php echo htmlspecialchars($product['slug']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo (int) $product['id']; ?>" class="btn-small">Sửa</a>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo (int) $product['id']; ?>">
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
