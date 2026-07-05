<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/cart-functions.php';
require_once __DIR__ . '/includes/customer-auth.php';

function hasPurchasedProduct(PDO $pdo, int $customerId, int $productId): bool
{
    if ($customerId <= 0 || $productId <= 0) {
        return false;
    }

    $stmt = $pdo->prepare(
        "SELECT 1
         FROM orders o
         INNER JOIN order_items oi ON oi.order_id = o.id
         WHERE o.customer_id = ?
           AND oi.product_id = ?
           AND (o.status = 'success' OR o.payment_status = 'paid')
         LIMIT 1"
    );
    $stmt->execute([$customerId, $productId]);

    return (bool) $stmt->fetchColumn();
}

function renderStarText(int $rating): string
{
    $rating = max(0, min(5, $rating));

    return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
}

include 'includes/header.php';
include 'includes/navbar.php';

$id = (int) ($_GET['id'] ?? 0);
$product = null;
$relatedProducts = [];
$successMessage = '';
$errorMessage = '';

$reviewSummary = [
    'total_reviews' => 0,
    'avg_rating' => 0,
];
$productReviews = [];
$customerReview = null;
$canReview = false;
$isCustomerLoggedIn = isCustomerLoggedIn();
$currentCustomer = getCurrentCustomer();
$defaultRatingValue = 5;
$defaultCommentValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    addToCart($pdo, $id, $quantity);
    $successMessage = 'Da them san pham vao gio hang.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $rating = (int) ($_POST['rating'] ?? 0);
    $comment = trim((string) ($_POST['comment'] ?? ''));
    $defaultRatingValue = $rating > 0 ? $rating : 5;
    $defaultCommentValue = $comment;

    if (!$isCustomerLoggedIn) {
        $errorMessage = 'Vui long dang nhap de gui danh gia san pham.';
    } elseif ($id <= 0) {
        $errorMessage = 'San pham khong hop le.';
    } elseif ($rating < 1 || $rating > 5) {
        $errorMessage = 'So sao danh gia phai tu 1 den 5.';
    } elseif (mb_strlen($comment, 'UTF-8') < 10) {
        $errorMessage = 'Noi dung danh gia toi thieu 10 ky tu.';
    } elseif (!hasPurchasedProduct($pdo, (int) $currentCustomer['id'], $id)) {
        $errorMessage = 'Ban chi co the danh gia sau khi da mua san pham nay.';
    } else {
        $upsertReviewStmt = $pdo->prepare(
            "INSERT INTO product_reviews (product_id, customer_id, rating, comment)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                rating = VALUES(rating),
                comment = VALUES(comment),
                updated_at = CURRENT_TIMESTAMP"
        );
        $upsertReviewStmt->execute([
            $id,
            (int) $currentCustomer['id'],
            $rating,
            $comment,
        ]);

        $successMessage = 'Danh gia cua ban da duoc ghi nhan.';
        $defaultRatingValue = 5;
        $defaultCommentValue = '';
    }
}

if ($id > 0) {
    $stmt = $pdo->prepare(
        "SELECT p.*, c.name AS category_name
         FROM products p
         LEFT JOIN categories c ON c.id = p.category_id
         WHERE p.id = ?"
    );
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$product) {
    header('Location: products.php');
    exit;
}

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
if (empty($galleryImages)) {
    $galleryImages[] = normalizeProductImagePath(null);
}

$specifications = [];
if (!empty($product['specifications'])) {
    $specifications = json_decode($product['specifications'], true);
    if (!is_array($specifications)) {
        $specifications = [];
    }
}

$relatedStmt = $pdo->prepare(
    "SELECT id, name, image, price
     FROM products
     WHERE category_id = ? AND id <> ?
     ORDER BY id DESC
     LIMIT 4"
);
$relatedStmt->execute([$product['category_id'], $product['id']]);
$relatedProducts = $relatedStmt->fetchAll(PDO::FETCH_ASSOC);

if ($isCustomerLoggedIn) {
    $canReview = hasPurchasedProduct($pdo, (int) $currentCustomer['id'], (int) $product['id']);

    $customerReviewStmt = $pdo->prepare('SELECT rating, comment FROM product_reviews WHERE product_id = ? AND customer_id = ? LIMIT 1');
    $customerReviewStmt->execute([(int) $product['id'], (int) $currentCustomer['id']]);
    $customerReview = $customerReviewStmt->fetch(PDO::FETCH_ASSOC) ?: null;

    if ($customerReview && $defaultCommentValue === '') {
        $defaultRatingValue = (int) ($customerReview['rating'] ?? 5);
        $defaultCommentValue = (string) ($customerReview['comment'] ?? '');
    }
}

$reviewSummaryStmt = $pdo->prepare('SELECT COUNT(*) AS total_reviews, COALESCE(AVG(rating), 0) AS avg_rating FROM product_reviews WHERE product_id = ?');
$reviewSummaryStmt->execute([(int) $product['id']]);
$reviewSummary = $reviewSummaryStmt->fetch(PDO::FETCH_ASSOC) ?: $reviewSummary;

$productReviewsStmt = $pdo->prepare(
    "SELECT pr.rating, pr.comment, pr.created_at, c.name AS customer_name
     FROM product_reviews pr
     INNER JOIN customers c ON c.id = pr.customer_id
     WHERE pr.product_id = ?
     ORDER BY pr.updated_at DESC, pr.id DESC
     LIMIT 20"
);
$productReviewsStmt->execute([(int) $product['id']]);
$productReviews = $productReviewsStmt->fetchAll(PDO::FETCH_ASSOC);

$averageRatingValue = (float) ($reviewSummary['avg_rating'] ?? 0);
$roundedAverageRating = (int) round($averageRatingValue);
$totalReviews = (int) ($reviewSummary['total_reviews'] ?? 0);
?>

<main>
    <section class="breadcrumb-section">
        <div class="container">
            <a href="index.php">Trang chu</a>
            <span>/</span>
            <a href="products.php">San pham</a>
            <span>/</span>
            <span><?php echo htmlspecialchars($product['name']); ?></span>
        </div>
    </section>

    <section class="product-detail">
        <div class="container">
            <div class="detail-wrapper">
                <div class="product-gallery">
                    <div class="main-image">
                        <img id="mainProductImage" src="<?php echo htmlspecialchars(normalizeProductImagePath($galleryImages[0])); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="thumb-list">
                        <?php foreach ($galleryImages as $image): ?>
                            <img class="thumb-image" src="<?php echo htmlspecialchars(normalizeProductImagePath($image)); ?>" alt="">
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="product-info">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <div class="rating">
                        <?php echo htmlspecialchars(renderStarText($roundedAverageRating)); ?>
                        <span>(<?php echo number_format($totalReviews); ?> danh gia - <?php echo number_format($averageRatingValue, 1); ?>/5)</span>
                    </div>

                    <div class="price-box">
                        <span class="new-price"><?php echo number_format((float) $product['price'], 0, ',', '.'); ?>d</span>
                    </div>

                    <div class="promotion">
                        <h4>Thong tin nhanh</h4>
                        <ul>
                            <li>Danh muc: <?php echo htmlspecialchars($product['category_name']); ?></li>
                            <li>Ton kho: <?php echo (int) $product['stock']; ?> san pham</li>
                            <li>Giao hang: 24 gio noi thanh</li>
                            <li>Bao hanh chinh hang</li>
                        </ul>
                    </div>

                    <?php if (!empty($successMessage)): ?>
                        <div class="alert alert-success" style="margin-bottom: 15px;"><?php echo htmlspecialchars($successMessage); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger" style="margin-bottom: 15px;"><?php echo htmlspecialchars($errorMessage); ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <input type="hidden" name="add_to_cart" value="1">
                        <div class="quantity">
                            <label>So luong</label>
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo (int) $product['stock']; ?>">
                        </div>
                        <div class="detail-button">
                            <button type="submit" class="add-cart">THEM GIO HANG</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="description">
        <div class="container">
            <h2>Mo ta san pham</h2>
            <p><?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?></p>
        </div>
    </section>

    <section class="specification">
        <div class="container">
            <h2>Thong so ky thuat</h2>
            <table>
                <?php if (!empty($specifications)): ?>
                    <?php foreach ($specifications as $name => $value): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <td><?php echo htmlspecialchars($value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2">Chua co thong so ky thuat.</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </section>

    <section class="review-section">
        <div class="container">
            <h2>Danh gia san pham</h2>
            <div class="review-summary-box">
                <div class="review-summary-score"><?php echo number_format($averageRatingValue, 1); ?>/5</div>
                <div>
                    <div class="review-summary-stars"><?php echo htmlspecialchars(renderStarText($roundedAverageRating)); ?></div>
                    <p>Dua tren <?php echo number_format($totalReviews); ?> danh gia thuc te tu khach da mua hang.</p>
                </div>
            </div>

            <?php if ($isCustomerLoggedIn && $canReview): ?>
                <form method="post" class="review-form">
                    <input type="hidden" name="submit_review" value="1">
                    <h3><?php echo $customerReview ? 'Cap nhat danh gia cua ban' : 'Gui danh gia cua ban'; ?></h3>
                    <div class="review-form-row">
                        <label for="rating">So sao</label>
                        <select id="rating" name="rating" required>
                            <?php for ($star = 5; $star >= 1; $star--): ?>
                                <option value="<?php echo $star; ?>" <?php echo $defaultRatingValue === $star ? 'selected' : ''; ?>><?php echo $star; ?> sao</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="review-form-row">
                        <label for="comment">Noi dung danh gia</label>
                        <textarea id="comment" name="comment" rows="4" maxlength="1000" required><?php echo htmlspecialchars($defaultCommentValue); ?></textarea>
                    </div>
                    <button type="submit" class="btn-review-submit">Gui danh gia</button>
                </form>
            <?php elseif ($isCustomerLoggedIn): ?>
                <div class="review-note">Ban can co don hang da thanh toan cho san pham nay de gui danh gia.</div>
            <?php else: ?>
                <div class="review-note">Vui long <a href="login.php">dang nhap</a> va mua san pham de gui danh gia thuc te.</div>
            <?php endif; ?>

            <div class="review-list">
                <?php if (empty($productReviews)): ?>
                    <div class="review-empty">San pham chua co danh gia nao.</div>
                <?php else: ?>
                    <?php foreach ($productReviews as $review): ?>
                        <article class="review-item">
                            <div class="review-item-top">
                                <strong><?php echo htmlspecialchars($review['customer_name'] ?? 'Khach hang'); ?></strong>
                                <span class="review-stars"><?php echo htmlspecialchars(renderStarText((int) ($review['rating'] ?? 0))); ?></span>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars((string) ($review['comment'] ?? ''))); ?></p>
                            <time><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime((string) ($review['created_at'] ?? 'now')))); ?></time>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="related-product">
        <div class="container">
            <div class="section-title"><h2>San pham lien quan</h2></div>
            <div class="product-grid">
                <?php if (!empty($relatedProducts)): ?>
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <div class="product-card">
                            <img src="<?php echo htmlspecialchars(normalizeProductImagePath($relatedProduct['image'] ?? null)); ?>" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
                            <h4><?php echo htmlspecialchars($relatedProduct['name']); ?></h4>
                            <div class="price"><span class="new-price"><?php echo number_format((float) $relatedProduct['price'], 0, ',', '.'); ?>d</span></div>
                            <a href="product-detail.php?id=<?php echo (int) $relatedProduct['id']; ?>">Xem chi tiet</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column:1 / -1;padding:20px;background:#f7f7f7;border-radius:8px;text-align:center;">Chua co san pham lien quan trong danh muc nay.</div>
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
