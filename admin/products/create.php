<?php

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';

$categories = $pdo->query("
    SELECT id, name
    FROM categories
    ORDER BY name
")->fetchAll();

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name        = trim($_POST['name'] ?? '');
    $slug        = trim($_POST['slug'] ?? '');
    $categoryId  = (int)($_POST['category_id'] ?? 0);
    $price       = (int)($_POST['price'] ?? 0);
    $stock       = (int)($_POST['stock'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    $cpu         = trim($_POST['cpu'] ?? '');
    $ram         = trim($_POST['ram'] ?? '');
    $storage     = trim($_POST['storage'] ?? '');
    $gpu         = trim($_POST['gpu'] ?? '');
    $display     = trim($_POST['display'] ?? '');
    $battery     = trim($_POST['battery'] ?? '');
    $os          = trim($_POST['os'] ?? '');
    $weight      = trim($_POST['weight'] ?? '');
    $warranty    = trim($_POST['warranty'] ?? '');

    $imagePath = null;

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $imagePath = uploadProductImage($_FILES['image']);

        if ($imagePath === null) {
            $errorMessage = 'Tải ảnh thất bại.';
        }

    }

    if ($name === '' || $slug === '') {

        $errorMessage = 'Vui lòng nhập đầy đủ thông tin.';

    }

    if ($errorMessage === '') {

        $stmt = $pdo->prepare("
            INSERT INTO products
            (
                category_id,
                name,
                slug,
                price,
                stock,
                description,
                image,
                cpu,
                ram,
                storage,
                gpu,
                display,
                battery,
                os,
                weight,
                warranty
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $stmt->execute([
            $categoryId ?: null,
            $name,
            $slug,
            $price,
            $stock,
            $description,
            $imagePath,
            $cpu,
            $ram,
            $storage,
            $gpu,
            $display,
            $battery,
            $os,
            $weight,
            $warranty
        ]);

        header("Location: index.php");
        exit;
    }

}

?>

<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Thêm sản phẩm</title>

    <link
        rel="stylesheet"
        href="../assets/css/admin.css?v=2"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

</head>

<body>

<div class="admin">

    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="main">

        <?php include __DIR__ . '/../includes/topbar.php'; ?>

        <div class="content">

            <h2>Thêm sản phẩm</h2>

            <p>Nhập thông tin sản phẩm.</p>

            <?php if ($successMessage): ?>
                <div class="alert success">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>

            <?php if ($errorMessage): ?>
                <div class="alert error">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>

            <div class="table-box">

                <form
                    method="post"
                    enctype="multipart/form-data"
                >
                                    <div class="form-group">
                        <label>Tên sản phẩm</label>
                        <input
                            type="text"
                            name="name"
                            required
                            placeholder="Ví dụ: ASUS TUF Gaming A15"
                        >
                    </div>

                    <div class="form-group">
                        <label>Slug</label>
                        <input
                            type="text"
                            name="slug"
                            required
                            placeholder="asus-tuf-gaming-a15"
                        >
                    </div>

                    <div class="form-group">
                        <label>Danh mục</label>

                        <select name="category_id">

                            <option value="0">
                                -- Chọn danh mục --
                            </option>

                            <?php foreach ($categories as $category): ?>

                                <option value="<?= $category['id']; ?>">

                                    <?= htmlspecialchars($category['name']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="form-group">
                        <label>Giá bán</label>

                        <input
                            type="number"
                            name="price"
                            min="0"
                            value="0"
                        >
                    </div>

                    <div class="form-group">
                        <label>Tồn kho</label>

                        <input
                            type="number"
                            name="stock"
                            min="0"
                            value="0"
                        >
                    </div>

                    <div class="form-group">
                        <label>Ảnh sản phẩm</label>

                        <input
                            type="file"
                            name="image"
                            accept="image/*"
                        >
                    </div>

                    <div class="form-group">
                        <label>Mô tả sản phẩm</label>

                        <textarea
                            name="description"
                            rows="6"
                            placeholder="Nhập mô tả sản phẩm..."
                        ></textarea>

                    </div>

                    <hr>

                    <h3 class="spec-title">

                        <i class="fa-solid fa-microchip"></i>

                        Thông số kỹ thuật

                    </h3>

                    <div class="spec-grid">

                        <div class="form-group">
                            <label>CPU</label>
                            <input
                                type="text"
                                name="cpu"
                                placeholder="Intel Core i7-14700HX"
                            >
                        </div>

                        <div class="form-group">
                            <label>RAM</label>
                            <input
                                type="text"
                                name="ram"
                                placeholder="16GB DDR5"
                            >
                        </div>

                        <div class="form-group">
                            <label>Ổ cứng</label>
                            <input
                                type="text"
                                name="storage"
                                placeholder="SSD 1TB NVMe"
                            >
                        </div>

                        <div class="form-group">
                            <label>Card đồ họa</label>
                            <input
                                type="text"
                                name="gpu"
                                placeholder="RTX 4060 8GB"
                            >
                        </div>

                        <div class="form-group">
                            <label>Màn hình</label>
                            <input
                                type="text"
                                name="display"
                                placeholder="15.6 inch FHD 144Hz"
                            >
                        </div>

                    </div>
                                        <div class="form-action">

                        <button
                            type="submit"
                            class="btn-primary"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Lưu sản phẩm
                        </button>

                        <a
                            href="index.php"
                            class="btn-secondary"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            Quay lại
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>