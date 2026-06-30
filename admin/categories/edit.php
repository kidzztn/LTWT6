<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';

$id = (int) ($_GET['id'] ?? 0);
$category = null;

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT id, name, slug FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    $category = $stmt->fetch();
}

if (!$category) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    $stmt = $pdo->prepare('UPDATE categories SET name = ?, slug = ? WHERE id = ?');
    $stmt->execute([$name, $slug, $id]);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa danh mục</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
<div class="admin">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main">
        <?php include __DIR__ . '/../includes/topbar.php'; ?>
        <div class="content">
            <h2>Sửa danh mục</h2>
            <p>Cập nhật thông tin nhóm sản phẩm.</p>
            <div class="table-box">
                <form method="post">
                    <div class="form-group">
                        <label>Tên danh mục</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" value="<?php echo htmlspecialchars($category['slug']); ?>" required>
                    </div>
                    <button type="submit" class="btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
