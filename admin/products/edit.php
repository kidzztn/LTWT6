<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';

$id = (int) ($_GET['id'] ?? 0);
$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$product = null;

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT id, category_id, name, slug, price, stock, description FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}

if (!$product) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $price = (int) ($_POST['price'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $imagePath = $product['image'] ?? null;

    if (isset($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $uploadedImage = uploadProductImage($_FILES['image']);
        if ($uploadedImage !== null) {
            $imagePath = $uploadedImage;
        }
    }

    $stmt = $pdo->prepare('UPDATE products SET category_id = ?, name = ?, slug = ?, price = ?, stock = ?, description = ?, image = ? WHERE id = ?');
    $stmt->execute([$categoryId > 0 ? $categoryId : null, $name, $slug, $price, $stock, $description, $imagePath, $id]);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
<div class="admin">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="content">
            <h2>Sửa sản phẩm</h2>
            <p>Cập nhật thông tin sản phẩm.</p>
            <div class="table-box">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tên sản phẩm</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" value="<?php echo htmlspecialchars($product['slug']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Danh mục</label>
                        <select name="category_id">
                            <option value="0">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo (int) $category['id']; ?>" <?php echo ((int) $product['category_id'] === (int) $category['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giá</label>
                        <input type="number" name="price" min="0" value="<?php echo (int) $product['price']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Tồn kho</label>
                        <input type="number" name="stock" min="0" value="<?php echo (int) $product['stock']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Ảnh sản phẩm</label>
                        <input type="file" name="image" accept="image/*">
                        <?php if (!empty($product['image'])): ?>
                            <div style="margin-top:10px;"><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Ảnh hiện tại" style="max-width:140px; border-radius:8px;"></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" rows="5"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>