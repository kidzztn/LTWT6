<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/cart-functions.php';
include 'includes/header.php';
include 'includes/navbar.php';

$id = (int) ($_GET['id'] ?? 0);
$product = null;
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    addToCart($pdo, $id, $quantity);
    $successMessage = 'Đã thêm sản phẩm vào giỏ hàng.';
}

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT p.id, p.name, p.slug, p.price, p.stock, p.description, p.image, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}

$relatedProducts = [];
if ($product) {
    $relatedStmt = $pdo->prepare('SELECT id, name, price, image FROM products WHERE category_id = ? AND id != ? ORDER BY id DESC LIMIT 4');
    $relatedStmt->execute([(int) ($product['id'] ?? 0), (int) ($product['id'] ?? 0)]);
    $relatedProducts = $relatedStmt->fetchAll();
}

if (!$product) {
    header('Location: products.php');
    exit;
}
?>

<main>

    <!-- Breadcrumb -->

    <section class="breadcrumb-section">

        <div class="container">

            <a href="index.php">Trang chủ</a>

            <span>/</span>

            <a href="products.php">Sản phẩm</a>

            <span>/</span>

            <span><?php echo htmlspecialchars($product['name']); ?></span>

        </div>

    </section>


    <!-- Product Detail -->

    <section class="product-detail">

        <div class="container">

            <div class="detail-wrapper">

                <!-- Left -->

                <div class="product-gallery">

                    <div class="main-image">

                        <img src="<?php echo htmlspecialchars(normalizeProductImagePath($product['image'] ?? null)); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

                    </div>

                    <div class="thumb-list">

                        <img src="../img/products/laptop.png">

                        <img src="../img/products/laptop.png">

                        <img src="../img/products/laptop.png">

                        <img src="../img/products/laptop.png">

                    </div>

                </div>

                <!-- Right -->

                <div class="product-info">

                    <h1>

                        <?php echo htmlspecialchars($product['name']); ?>

                    </h1>

                    <div class="rating">

                        ★★★★★

                        <span>(256 đánh giá)</span>

                    </div>

                    <div class="price-box">

                        <span class="new-price">

                            <?php echo number_format((float) $product['price'], 0, ',', '.'); ?>₫

                        </span>

                    </div>

                    <div class="promotion">

                        <h4>Thông tin nhanh</h4>

                        <ul>

                            <li>✔ Danh mục: <?php echo htmlspecialchars($product['category_name'] ?? 'Khác'); ?></li>

                            <li>✔ Tồn kho: <?php echo (int) $product['stock']; ?> sản phẩm</li>

                            <li>✔ Giao hàng: 24 giờ nội thành</li>

                            <li>✔ Bảo hành chính hãng</li>

                        </ul>

                    </div>

                    <?php if (!empty($successMessage)): ?>
                        <div class="alert alert-success" style="margin-bottom: 15px;"><?php echo htmlspecialchars($successMessage); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger" style="margin-bottom: 15px;"><?php echo htmlspecialchars($errorMessage); ?></div>
                    <?php endif; ?>

                    <form method="post" style="display:block;">
                        <input type="hidden" name="add_to_cart" value="1">
                        <div class="quantity">
                            <label>Số lượng</label>
                            <input type="number" name="quantity" value="1" min="1">
                        </div>

                        <div class="detail-button">
                            <button type="submit" class="add-cart">
                                THÊM GIỎ HÀNG
                            </button>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </section>



    <!-- Description -->

    <section class="description">

        <div class="container">

            <h2>Mô tả sản phẩm</h2>

            <p>

                <?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?>

            </p>

        </div>

    </section>



    <!-- Specification -->

    <section class="specification">

        <div class="container">

            <h2>Thông số kỹ thuật</h2>

            <table>

                <tr>

                    <td>CPU</td>

                    <td>Intel Core i7-14700HX</td>

                </tr>

                <tr>

                    <td>RAM</td>

                    <td>16GB DDR5</td>

                </tr>

                <tr>

                    <td>Ổ cứng</td>

                    <td>SSD 1TB NVMe</td>

                </tr>

                <tr>

                    <td>Card đồ họa</td>

                    <td>RTX4060 8GB</td>

                </tr>

                <tr>

                    <td>Màn hình</td>

                    <td>16 inch 165Hz</td>

                </tr>

            </table>

        </div>

    </section>



    <!-- Related -->

    <section class="related-product">

        <div class="container">

            <div class="section-title">

                <h2>Sản phẩm liên quan</h2>

            </div>

            <div class="product-grid">

                <?php if (!empty($relatedProducts)): ?>
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <div class="product-card">
                            <img src="<?php echo htmlspecialchars(normalizeProductImagePath($relatedProduct['image'] ?? null)); ?>" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
                            <h4><?php echo htmlspecialchars($relatedProduct['name']); ?></h4>
                            <div class="price">
                                <span class="new-price">
                                    <?php echo number_format((float) $relatedProduct['price'], 0, ',', '.'); ?>₫
                                </span>
                            </div>
                            <a href="product-detail.php?id=<?php echo (int) $relatedProduct['id']; ?>">Xem chi tiết</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; padding: 20px; background: #f7f7f7; border-radius: 8px;">
                        Chưa có sản phẩm liên quan trong danh mục này.
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </section>

</main>

<?php
include 'includes/footer.php';
?>