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

$searchQuery = trim($_GET['query'] ?? '');
$selectedCategorySlug = trim($_GET['category'] ?? '');
$selectedPriceFilter = trim($_GET['price'] ?? '');
$selectedCategory = null;

if ($selectedCategorySlug !== '') {
    $stmt = $pdo->prepare('SELECT id, name, slug FROM categories WHERE slug = ?');
    $stmt->execute([$selectedCategorySlug]);
    $selectedCategory = $stmt->fetch();
}

$sql = 'SELECT p.id, p.name, p.slug, p.price, p.stock, p.image, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE 1=1';
$params = [];

if ($selectedCategory) {
    $sql .= ' AND p.category_id = ?';
    $params[] = (int) $selectedCategory['id'];
}

if ($searchQuery !== '') {
    $sql .= ' AND (p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)';
    $likeQuery = '%' . $searchQuery . '%';
    $params[] = $likeQuery;
    $params[] = $likeQuery;
    $params[] = $likeQuery;
}

if ($selectedPriceFilter !== '') {
    switch ($selectedPriceFilter) {
        case 'under-10':
            $sql .= ' AND p.price < ?';
            $params[] = 10000000;
            break;
        case '10-20':
            $sql .= ' AND p.price >= ? AND p.price < ?';
            $params[] = 10000000;
            $params[] = 20000000;
            break;
        case '20-30':
            $sql .= ' AND p.price >= ? AND p.price < ?';
            $params[] = 20000000;
            $params[] = 30000000;
            break;
        case 'above-30':
            $sql .= ' AND p.price >= ?';
            $params[] = 30000000;
            break;
    }
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

                        <h3>Tìm kiếm nhanh</h3>

                        <form method="get" class="filter-form">
                            <input type="text" name="query" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Tên sản phẩm...">
                            <select name="category">
                                <option value="">Tất cả danh mục</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category['slug']); ?>" <?php echo $selectedCategory && $selectedCategory['slug'] === $category['slug'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="price">
                                <option value="">Tất cả mức giá</option>
                                <option value="under-10" <?php echo $selectedPriceFilter === 'under-10' ? 'selected' : ''; ?>>Dưới 10 triệu</option>
                                <option value="10-20" <?php echo $selectedPriceFilter === '10-20' ? 'selected' : ''; ?>>10 - 20 triệu</option>
                                <option value="20-30" <?php echo $selectedPriceFilter === '20-30' ? 'selected' : ''; ?>>20 - 30 triệu</option>
                                <option value="above-30" <?php echo $selectedPriceFilter === 'above-30' ? 'selected' : ''; ?>>Trên 30 triệu</option>
                            </select>
                            <button type="submit">Áp dụng</button>
                            <a href="products.php">Bỏ lọc</a>
                        </form>

                    </div>

                </aside>

                <!-- Content -->

                <div class="product-content">

                    <div class="product-toolbar">

                        <div>
                            <h2><?php echo $selectedCategory ? htmlspecialchars($selectedCategory['name']) : 'Tất cả sản phẩm'; ?></h2>
                            <p class="search-summary">
                                <?php echo $searchQuery !== '' ? 'Kết quả cho từ khóa "' . htmlspecialchars($searchQuery) . '"' : 'Khám phá bộ sưu tập công nghệ mới nhất'; ?>
                            </p>
                        </div>

                        <div class="toolbar-meta">
                            <?php echo count($products); ?> sản phẩm
                        </div>

                    </div>

                    <div class="product-grid">

                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <a href="product-detail.php?id=<?php echo (int) $product['id']; ?>" class="product-card" style="text-decoration:none; color:inherit; display:block;">
                                    <img src="<?php echo htmlspecialchars(normalizeProductImagePath($product['image'] ?? null)); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <p class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Sản phẩm'); ?></p>
                                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                    <div class="product-meta">
                                        <span class="stock-badge">Còn hàng</span>
                                        <span class="rating">★★★★★</span>
                                    </div>
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

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<?php
include 'includes/footer.php';
?>