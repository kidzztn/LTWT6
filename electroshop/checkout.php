<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/cart-functions.php';
require_once __DIR__ . '/includes/customer-auth.php';
include 'includes/header.php';
include 'includes/navbar.php';

$cart = getCart();
$cartItems = [];
$total = 0.0;

foreach ($cart as $item) {
    $itemTotal = (float)$item['price'] * (int)$item['quantity'];
    $total += $itemTotal;
    $cartItems[] = [
        'id' => (int)$item['id'],
        'name' => (string)$item['name'],
        'price' => (float)$item['price'],
        'quantity' => (int)$item['quantity'],
        'total' => $itemTotal,
    ];
}

$successMessage = '';
$errorMessage   = '';
$loggedCustomer = getCurrentCustomer();
$customerProfile = null;

$loggedCustomerId = isset($loggedCustomer['id']) ? (int)$loggedCustomer['id'] : 0;

if ($loggedCustomerId > 0) {
    $stmt = $pdo->prepare('SELECT id, name, email, phone, address FROM customers WHERE id = ? LIMIT 1');
    $stmt->execute([$loggedCustomerId]);
    $customerProfile = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

if (empty($cartItems)) {
    $errorMessage = 'Giỏ hàng đang trống. Vui lòng thêm sản phẩm trước khi thanh toán.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name    = trim($_POST['name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '' || $phone === '' || $address === '') {
        $errorMessage = 'Vui lòng nhập đầy đủ họ tên, số điện thoại và địa chỉ.';
    } elseif (empty($cartItems)) {
        $errorMessage = 'Giỏ hàng đang trống.';
    } else {
        try {
            // Bật throw exception cho PDO để bắt lỗi FK rõ ràng
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $pdo->beginTransaction();

            $customerId = null;

            // 1) Nếu đã đăng nhập -> kiểm tra customer có tồn tại thật không
            if ($loggedCustomerId > 0) {
                $check = $pdo->prepare('SELECT id FROM customers WHERE id = ? LIMIT 1');
                $check->execute([$loggedCustomerId]);
                $existingId = $check->fetchColumn();

                if ($existingId) {
                    $customerId = (int)$existingId;

                    // Cập nhật hồ sơ người dùng hiện tại
                    $upd = $pdo->prepare('UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?');
                    $upd->execute([$name, $email !== '' ? $email : null, $phone, $address, $customerId]);
                } else {
                    // Session id "ma" => tạo customer mới để tránh FK fail
                    $insCus = $pdo->prepare('INSERT INTO customers (name, email, phone, address, password_hash) VALUES (?, ?, ?, ?, NULL)');
                    $insCus->execute([$name, $email !== '' ? $email : null, $phone, $address]);
                    $customerId = (int)$pdo->lastInsertId();
                }
            } else {
                // 2) Guest checkout -> tạo customer mới
                $insCus = $pdo->prepare('INSERT INTO customers (name, email, phone, address, password_hash) VALUES (?, ?, ?, ?, NULL)');
                $insCus->execute([$name, $email !== '' ? $email : null, $phone, $address]);
                $customerId = (int)$pdo->lastInsertId();
            }

            // 3) Chốt an toàn FK trước khi insert order
            if (!is_int($customerId) || $customerId <= 0) {
                throw new RuntimeException('customer_id không hợp lệ trước khi tạo đơn hàng.');
            }

            $check2 = $pdo->prepare('SELECT id FROM customers WHERE id = ? LIMIT 1');
            $check2->execute([$customerId]);
            if (!$check2->fetchColumn()) {
                throw new RuntimeException('customer_id không tồn tại trong bảng customers.');
            }

            // 4) Tạo order
            $insOrder = $pdo->prepare('INSERT INTO orders (customer_id, total, status) VALUES (?, ?, "pending")');
            $insOrder->execute([$customerId, (float)$total]);
            $orderId = (int)$pdo->lastInsertId();

            if ($orderId <= 0) {
                throw new RuntimeException('Không lấy được order_id sau khi insert orders.');
            }

            // 5) Tạo order items
            $insItem = $pdo->prepare('
                INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
                VALUES (?, ?, ?, ?, ?)
            ');

            foreach ($cartItems as $item) {
                $productId = (int)$item['id'];

                // kiểm tra sản phẩm tồn tại (tránh lỗi FK order_items_ibfk_2)
                $pcheck = $pdo->prepare('SELECT id FROM products WHERE id = ? LIMIT 1');
                $pcheck->execute([$productId]);
                if (!$pcheck->fetchColumn()) {
                    throw new RuntimeException('Sản phẩm #' . $productId . ' không tồn tại.');
                }

                $insItem->execute([
                    $orderId,
                    $productId,
                    (string)$item['name'],
                    (float)$item['price'],
                    (int)$item['quantity']
                ]);
            }

            $pdo->commit();
            clearCart();

            $successMessage = 'Đặt hàng thành công. Mã đơn hàng #' . $orderId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            // Log kỹ thuật để debug
            error_log('[CHECKOUT_ERROR] ' . $e->getMessage());

            // Thông báo thân thiện cho user
            $errorMessage = 'Đặt hàng thất bại: ' . $e->getMessage();
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

                        <input type="text" name="name" placeholder="Họ tên"
                               value="<?php echo htmlspecialchars($customerProfile['name'] ?? $loggedCustomer['name'] ?? ''); ?>" required>

                        <input type="text" name="phone" placeholder="Số điện thoại"
                               value="<?php echo htmlspecialchars($customerProfile['phone'] ?? ''); ?>" required>

                        <input type="email" name="email" placeholder="Email"
                               value="<?php echo htmlspecialchars($customerProfile['email'] ?? $loggedCustomer['email'] ?? ''); ?>">

                        <input type="text" name="address" placeholder="Địa chỉ"
                               value="<?php echo htmlspecialchars($customerProfile['address'] ?? ''); ?>" required>

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
                            <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo (int)$item['quantity']; ?></span>
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