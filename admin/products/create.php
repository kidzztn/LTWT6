<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $price = (int) ($_POST['price'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $slug === '') {
        $errorMessage = 'Vui lòng nhập tên và slug sản phẩm.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO products (category_id, name, slug, price, stock, description) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$categoryId > 0 ? $categoryId : null, $name, $slug, $price, $stock, $description]);
        $successMessage = 'Thêm sản phẩm thành công.';
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
<div class="admin">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="content">
            <h2>Thêm sản phẩm</h2>
            <p>Nhập thông tin sản phẩm mới.</p>

            <?php if (!empty($successMessage)): ?>
                <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>

            <div class="table-box">
                <form method="post">
                    <div class="form-group">
                        <label>Tên sản phẩm</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" required>
                    </div>
                    <div class="form-group">
                        <label>Danh mục</label>
                        <select name="category_id">
                            <option value="0">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo (int) $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giá</label>
                        <input type="number" name="price" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label>Tồn kho</label>
                        <input type="number" name="stock" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Lưu sản phẩm</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
