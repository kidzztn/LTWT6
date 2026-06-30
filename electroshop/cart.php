<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/cart-functions.php';
include 'includes/header.php';
include 'includes/navbar.php';

$cart = getCart();
$cartItems = [];
$total = 0;

foreach ($cart as $item) {
    $itemTotal = (float) $item['price'] * (int) $item['quantity'];
    $total += $itemTotal;
    $cartItems[] = [
        'id' => (int) $item['id'],
        'name' => $item['name'],
        'price' => (float) $item['price'],
        'quantity' => (int) $item['quantity'],
        'image' => $item['image'],
        'total' => $itemTotal,
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update') {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = max(0, (int) ($_POST['quantity'] ?? 0));
        updateCartItem($productId, $quantity);
        header('Location: cart.php');
        exit;
    }

    if ($_POST['action'] === 'remove') {
        $productId = (int) ($_POST['product_id'] ?? 0);
        removeCartItem($productId);
        header('Location: cart.php');
        exit;
    }
}
?>

<main>
    <section class="cart-page">
        <div class="container">
            <h2>Giỏ hàng</h2>

            <?php if (empty($cartItems)): ?>
                <div class="alert alert-info">Giỏ hàng của bạn đang trống.</div>
            <?php else: ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($item['image'] ?? '../img/products/default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</td>
                                <td>
                                    <form method="post" style="display:flex; gap:8px; align-items:center;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?php echo (int) $item['id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo (int) $item['quantity']; ?>" min="1" style="width:70px;">
                                        <button type="submit">Cập nhật</button>
                                    </form>
                                </td>
                                <td><?php echo number_format($item['total'], 0, ',', '.'); ?>₫</td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?php echo (int) $item['id']; ?>">
                                        <button type="submit">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-total">
                    <h3>Tổng cộng <?php echo number_format($total, 0, ',', '.'); ?>₫</h3>
                    <a href="checkout.php" class="checkout-btn">Thanh toán</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>