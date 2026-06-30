<?php
require_once __DIR__ . '/../config/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

$categories = $pdo->query('SELECT id, name, slug FROM categories')->fetchAll();
$priorityOrder = ['laptop' => 0, 'dien-thoai' => 1, 'linh-kien-may-tinh' => 2];

usort($categories, function ($a, $b) use ($priorityOrder) {
    $aPriority = $priorityOrder[$a['slug']] ?? 99;
    $bPriority = $priorityOrder[$b['slug']] ?? 99;

    if ($aPriority !== $bPriority) {
        return $aPriority <=> $bPriority;
    }

    return strcmp($a['name'], $b['name']);
});

$selectedCategorySlug = trim($_GET['category'] ?? '');
$selectedCategory = null;

if ($selectedCategorySlug !== '') {
    $stmt = $pdo->prepare('SELECT id, name, slug FROM categories WHERE slug = ?');
    $stmt->execute([$selectedCategorySlug]);
    $selectedCategory = $stmt->fetch();
}

$sql = 'SELECT p.id, p.name, p.slug, p.price, p.stock, p.image, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id';
$params = [];

if ($selectedCategory) {
    $sql .= ' WHERE p.category_id = ?';
    $params[] = (int) $selectedCategory['id'];
}

$sql .= ' ORDER BY p.id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<main class="products-page">

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <a href="index.php">Trang chủ</a>
            <span>/</span>
            <span>Sản phẩm</span>
        </div>
    </section>

    <section class="products">

        <div class="container">

            <div class="products-layout">

                <!-- Sidebar -->

                <aside class="sidebar">

                    <div class="filter-box">

                        <h3>Danh mục</h3>

                        <ul>
                            <li><a href="products.php" class="<?php echo $selectedCategory ? '' : 'active'; ?>">Tất cả sản phẩm</a></li>
                            <?php foreach ($categories as $category): ?>
                                <li><a href="products.php?category=<?php echo urlencode($category['slug']); ?>" class="<?php echo $selectedCategory && $selectedCategory['id'] === (int) $category['id'] ? 'active' : ''; ?>"><?php echo htmlspecialchars($category['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>

                    </div>

                    <div class="filter-box">

                        <h3>Mức giá</h3>

                        <ul>

                            <li><input type="checkbox"> Dưới 10 triệu</li>

                            <li><input type="checkbox"> 10 - 20 triệu</li>

                            <li><input type="checkbox"> 20 - 30 triệu</li>

                            <li><input type="checkbox"> Trên 30 triệu</li>

                        </ul>

                    </div>

                    <div class="filter-box">

                        <h3>Thương hiệu</h3>

                        <ul>

                            <li><input type="checkbox"> ASUS</li>

                            <li><input type="checkbox"> MSI</li>

                            <li><input type="checkbox"> Dell</li>

                            <li><input type="checkbox"> Lenovo</li>

                            <li><input type="checkbox"> Acer</li>

                            <li><input type="checkbox"> HP</li>

                            <li><input type="checkbox"> Apple</li>

                        </ul>

                    </div>

                </aside>

                <!-- Content -->

                <div class="product-content">

                    <div class="product-toolbar">

                        <h2><?php echo $selectedCategory ? htmlspecialchars($selectedCategory['name']) : 'Tất cả sản phẩm'; ?></h2>

                        <select>

                            <option>Sắp xếp mới nhất</option>

                            <option>Giá tăng dần</option>

                            <option>Giá giảm dần</option>

                            <option>Bán chạy</option>

                        </select>

                    </div>

                    <div class="product-grid">

                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <a href="product-detail.php?id=<?php echo (int) $product['id']; ?>" class="product-card" style="text-decoration:none; color:inherit; display:block;">
                                    <img src="<?php echo htmlspecialchars($product['image'] ?? '../img/products/default.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                    <p><?php echo htmlspecialchars($product['category_name'] ?? '-'); ?></p>
                                    <div class="price">
                                        <span class="new-price">
                                            <?php echo number_format((float) $product['price'], 0, ',', '.'); ?>₫
                                        </span>
                                    </div>
                                    <div class="product-action">
                                        <span>Xem chi tiết</span>
                                        <button type="button">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </button>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="grid-column: 1 / -1; padding: 20px; background: #f7f7f7; border-radius: 8px;">
                                Chưa có sản phẩm nào trong danh mục này.
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="pagination">

                        <a href="#">«</a>

                        <a class="active" href="#">1</a>

                        <a href="#">2</a>

                        <a href="#">3</a>

                        <a href="#">4</a>

                        <a href="#">»</a>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<?php
include 'includes/footer.php';
?>