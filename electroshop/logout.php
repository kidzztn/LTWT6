<?php
require_once __DIR__ . '/includes/cart-functions.php';
require_once __DIR__ . '/includes/customer-auth.php';
customerLogout();
clearCart();
header('Location: login.php');
exit;
