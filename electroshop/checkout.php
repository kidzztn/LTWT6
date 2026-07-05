<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/cart-functions.php';
require_once __DIR__ . '/includes/customer-auth.php';
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
        'total' => $itemTotal,
    ];
}

$successMessage = '';
$errorMessage = '';
$loggedCustomer = getCurrentCustomer();
$customerProfile = null;

if ($loggedCustomer['id'] > 0) {
    $stmt = $pdo->prepare('SELECT id, name, email, phone, address FROM customers WHERE id = ?');
    $stmt->execute([$loggedCustomer['id']]);
    $customerProfile = $stmt->fetch();
}

if (empty($cartItems)) {
    $errorMessage = 'Giỏ hàng đang trống. Vui lòng thêm sản phẩm trước khi thanh toán.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($cartItems)) {
        $errorMessage = 'Giỏ hàng đang trống.';
    } else {
        try {
            $pdo->beginTransaction();

            $customerId = null;
            if ($loggedCustomer['id'] > 0) {
                $stmt = $pdo->prepare('SELECT id FROM customers WHERE id = ? LIMIT 1');
                $stmt->execute([(int) $loggedCustomer['id']]);
                $existingCustomerId = $stmt->fetchColumn();

                if ($existingCustomerId) {
                    $customerId = (int) $existingCustomerId;
                    $resolvedName = $name !== '' ? $name : ($customerProfile['name'] ?? $loggedCustomer['name'] ?? 'Guest');
                    $resolvedEmail = $email !== '' ? $email : ($customerProfile['email'] ?? $loggedCustomer['email'] ?? null);
                    $resolvedPhone = $phone !== '' ? $phone : ($customerProfile['phone'] ?? null);
                    $resolvedAddress = $address !== '' ? $address : ($customerProfile['address'] ?? null);
                    $stmt = $pdo->prepare('UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?');
                    $stmt->execute([$resolvedName, $resolvedEmail, $resolvedPhone, $resolvedAddress, $customerId]);
                }
            }

            if ($customerId === null && ($name !== '' || $email !== '' || $phone !== '')) {
                $stmt = $pdo->prepare('INSERT INTO customers (name, email, phone, address, password_hash) VALUES (?, ?, ?, ?, NULL)');
                $stmt->execute([$name !== '' ? $name : 'Guest', $email !== '' ? $email : null, $phone !== '' ? $phone : null, $address !== '' ? $address : null]);
                $customerId = (int) $pdo->lastInsertId();
            }

            $stmt = $pdo->prepare('INSERT INTO orders (customer_id, total, status) VALUES (?, ?, "pending")');
            $validatedCartItems = [];
            $validatedTotal = 0;
            $productStmt = $pdo->prepare('SELECT id, name, price, stock FROM products WHERE id = ? FOR UPDATE');

            foreach ($cartItems as $item) {
                $quantity = (int) $item['quantity'];
                if ($quantity <= 0) {
                    throw new RuntimeException('Dữ liệu giỏ hàng không hợp lệ.');
                }

                $productStmt->execute([(int) $item['id']]);
                $product = $productStmt->fetch();
                if (!$product) {
                    throw new RuntimeException('Sản phẩm không tồn tại.');
                }

                if ((int) $product['stock'] < $quantity) {
                    throw new RuntimeException('Sản phẩm "' . $product['name'] . '" không đủ tồn kho.');
                }

                $lineTotal = (float) $product['price'] * $quantity;
                $validatedTotal += $lineTotal;
                $validatedCartItems[] = [
                    'id' => (int) $product['id'],
                    'name' => $product['name'],
                    'price' => (float) $product['price'],
                    'quantity' => $quantity,
                ];
            }

            $stmt->execute([$customerId, (float) $validatedTotal]);
            $orderId = (int) $pdo->lastInsertId();

            $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)');
            $updateStockStmt = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');
            foreach ($validatedCartItems as $item) {
                $stmt->execute([$orderId, $item['id'], $item['name'], (float) $item['price'], (int) $item['quantity']]);
                $updateStockStmt->execute([(int) $item['quantity'], (int) $item['id'], (int) $item['quantity']]);
                if ($updateStockStmt->rowCount() === 0) {
                    throw new RuntimeException('Không thể cập nhật tồn kho.');
                }
            }

            $pdo->commit();
            clearCart();
            $successMessage = 'Đặt hàng thành công. Mã đơn hàng #' . $orderId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errorMessage = 'Đặt hàng thất bại. Vui lòng thử lại.';
        }
    }
}
?>

<main>
    <section class="checkout-page">
        <div class="container">
            <div class="checkout-layout">
                <div class="checkout-left">
                    <h2>Thông tin nhận hàng</h2>

                    <?php if (!empty($successMessage)): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <input type="hidden" name="place_order" value="1">
                        <input type="text" name="name" placeholder="Họ tên" value="<?php echo htmlspecialchars($customerProfile['name'] ?? $loggedCustomer['name'] ?? ''); ?>">
                        <input type="text" name="phone" placeholder="Số điện thoại" value="<?php echo htmlspecialchars($customerProfile['phone'] ?? ''); ?>">
                        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($customerProfile['email'] ?? $loggedCustomer['email'] ?? ''); ?>">
                        <input type="text" name="address" placeholder="Địa chỉ" value="<?php echo htmlspecialchars($customerProfile['address'] ?? ''); ?>">
                        <select name="payment_method">
                            <option value="cash">Thanh toán khi nhận hàng</option>
                            <option value="transfer">Chuyển khoản</option>
                        </select>
                        <textarea name="note" placeholder="Ghi chú"></textarea>
                        <button type="submit">Đặt hàng</button>
                    </form>
                </div>

                <div class="checkout-right">
                    <h2>Đơn hàng</h2>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="order-item">
                            <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo (int) $item['quantity']; ?></span>
                            <span><?php echo number_format($item['total'], 0, ',', '.'); ?>₫</span>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <h3>Tổng <?php echo number_format($total, 0, ',', '.'); ?>₫</h3>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>