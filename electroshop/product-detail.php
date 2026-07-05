<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/cart-functions.php';

include 'includes/header.php';
include 'includes/navbar.php';

$id = (int) ($_GET['id'] ?? 0);

$product = null;
$relatedProducts = [];

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

    addToCart($pdo, $id, $quantity);

    $successMessage = 'Đã thêm sản phẩm vào giỏ hàng.';
}

if ($id > 0) {
    $stmt = $pdo->prepare("
        SELECT
            p.*,
            c.name AS category_name
        FROM products p
        LEFT JOIN categories c
            ON c.id = p.category_id
        WHERE p.id = ?
    ");

    $stmt->execute([$id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$product) {
    header('Location: products.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Gallery Images
|--------------------------------------------------------------------------
|
| Cột images lưu dạng JSON:
|
| [
|   "uploads/products/1.jpg",
|   "uploads/products/2.jpg",
|   "uploads/products/3.jpg"
| ]
|
*/

$galleryImages = [];

if (!empty($product['images'])) {
    $galleryImages = json_decode($product['images'], true);

    if (!is_array($galleryImages)) {
        $galleryImages = [];
    }
}

if (empty($galleryImages) && !empty($product['image'])) {
    $galleryImages[] = $product['image'];
}

 
$specifications = [];

if (!empty($product['specifications'])) {
    $specifications = json_decode($product['specifications'], true);

    if (!is_array($specifications)) {
        $specifications = [];
    }
}



$relatedStmt = $pdo->prepare("
    SELECT
        id,
        name,
        image,
        price
    FROM products
    WHERE category_id = ?
        AND id <> ?
    ORDER BY id DESC
    LIMIT 4
");

$relatedStmt->execute([
    $product['category_id'],
    $product['id']
]);

$relatedProducts = $relatedStmt->fetchAll(PDO::FETCH_ASSOC);
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

                <!-- Gallery -->

                <div class="product-gallery">

                    <div class="main-image">

                        <img
                            id="mainProductImage"
                            src="<?php echo htmlspecialchars(normalizeProductImagePath($galleryImages[0])); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                        >

                    </div>

                    <div class="thumb-list">

                        <?php foreach ($galleryImages as $image): ?>

                            <img
                                class="thumb-image"
                                src="<?php echo htmlspecialchars(normalizeProductImagePath($image)); ?>"
                                alt=""
                            >

                        <?php endforeach; ?>

                    </div>

                </div>

                <!-- Product Info -->

                <div class="product-info">

                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>

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

                            <li>
                                ✔ Danh mục:
                                <?php echo htmlspecialchars($product['category_name']); ?>
                            </li>

                            <li>
                                ✔ Tồn kho:
                                <?php echo (int) $product['stock']; ?> sản phẩm
                            </li>

                            <li>✔ Giao hàng: 24 giờ nội thành</li>

                            <li>✔ Bảo hành chính hãng</li>

                        </ul>

                    </div>
                                        <?php if (!empty($successMessage)): ?>

                        <div class="alert alert-success" style="margin-bottom: 15px;">

                            <?php echo htmlspecialchars($successMessage); ?>

                        </div>

                    <?php endif; ?>

                    <?php if (!empty($errorMessage)): ?>

                        <div class="alert alert-danger" style="margin-bottom: 15px;">

                            <?php echo htmlspecialchars($errorMessage); ?>

                        </div>

                    <?php endif; ?>

                    <form method="post">

                        <input type="hidden" name="add_to_cart" value="1">

                        <div class="quantity">

                            <label>Số lượng</label>

                            <input
                                type="number"
                                name="quantity"
                                value="1"
                                min="1"
                                max="<?php echo (int) $product['stock']; ?>"
                            >

                        </div>

                        <div class="detail-button">

                            <button
                                type="submit"
                                class="add-cart"
                            >
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

                <?php if (!empty($specifications)): ?>

                    <?php foreach ($specifications as $name => $value): ?>

                        <tr>

                            <td>

                                <?php echo htmlspecialchars($name); ?>

                            </td>

                            <td>

                                <?php echo htmlspecialchars($value); ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="2">

                            Chưa có thông số kỹ thuật.

                        </td>

                    </tr>

                <?php endif; ?>

            </table>

        </div>

    </section>

    <!-- Related Products -->

    <section class="related-product">

        <div class="container">

            <div class="section-title">

                <h2>Sản phẩm liên quan</h2>

            </div>

            <div class="product-grid">
                                <?php if (!empty($relatedProducts)): ?>

                    <?php foreach ($relatedProducts as $relatedProduct): ?>

                        <div class="product-card">

                            <img
                                src="<?php echo htmlspecialchars(normalizeProductImagePath($relatedProduct['image'] ?? null)); ?>"
                                alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>"
                            >

                            <h4>

                                <?php echo htmlspecialchars($relatedProduct['name']); ?>

                            </h4>

                            <div class="price">

                                <span class="new-price">

                                    <?php echo number_format((float) $relatedProduct['price'], 0, ',', '.'); ?>₫

                                </span>

                            </div>

                            <a
                                href="product-detail.php?id=<?php echo (int) $relatedProduct['id']; ?>"
                            >
                                Xem chi tiết
                            </a>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div
                        style="
                            grid-column: 1 / -1;
                            padding: 20px;
                            background: #f7f7f7;
                            border-radius: 8px;
                            text-align: center;
                        "
                    >

                        Chưa có sản phẩm liên quan trong danh mục này.

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const mainImage = document.getElementById('mainProductImage');

    const thumbs = document.querySelectorAll('.thumb-image');

    thumbs.forEach(function (thumb) {

        thumb.addEventListener('click', function () {

            mainImage.src = this.src;

            thumbs.forEach(function (img) {

                img.classList.remove('active');

            });

            this.classList.add('active');

        });

    });

});

</script>