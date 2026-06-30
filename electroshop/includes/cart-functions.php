<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getCart(): array
{
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    return $_SESSION['cart'];
}

function getCartCount(): int
{
    $cart = getCart();
    $count = 0;

    foreach ($cart as $item) {
        $count += max(1, (int) ($item['quantity'] ?? 1));
    }

    return $count;
}

function addToCart(PDO $pdo, int $productId, int $quantity = 1): void
{
    if ($productId <= 0 || $quantity <= 0) {
        return;
    }

    $stmt = $pdo->prepare('SELECT id, name, price, image FROM products WHERE id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product) {
        return;
    }

    $cart = getCart();

    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] += $quantity;
    } else {
        $cart[$productId] = [
            'id' => (int) $product['id'],
            'name' => $product['name'],
            'price' => (float) $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity,
        ];
    }

    $_SESSION['cart'] = $cart;
}

function updateCartItem(int $productId, int $quantity): void
{
    if ($productId <= 0) {
        return;
    }

    $cart = getCart();

    if (!isset($cart[$productId])) {
        return;
    }

    if ($quantity <= 0) {
        unset($cart[$productId]);
    } else {
        $cart[$productId]['quantity'] = $quantity;
    }

    $_SESSION['cart'] = $cart;
}

function removeCartItem(int $productId): void
{
    if ($productId <= 0) {
        return;
    }

    $cart = getCart();
    unset($cart[$productId]);
    $_SESSION['cart'] = $cart;
}

function clearCart(): void
{
    $_SESSION['cart'] = [];
}
