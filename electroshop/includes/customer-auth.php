<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isCustomerLoggedIn(): bool
{
    return !empty($_SESSION['customer_id']);
}

function getCurrentCustomer(): array
{
    return [
        'id' => (int) ($_SESSION['customer_id'] ?? 0),
        'name' => $_SESSION['customer_name'] ?? '',
        'email' => $_SESSION['customer_email'] ?? '',
    ];
}

function customerLogout(): void
{
    unset($_SESSION['customer_id'], $_SESSION['customer_name'], $_SESSION['customer_email']);
}
