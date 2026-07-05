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
        'provider' => $_SESSION['customer_provider'] ?? 'email',
        'avatar' => $_SESSION['customer_avatar'] ?? '',
    ];
}

function loginCustomerSession(array $customer): void
{
    $_SESSION['customer_id'] = (int) ($customer['id'] ?? 0);
    $_SESSION['customer_name'] = (string) ($customer['name'] ?? '');
    $_SESSION['customer_email'] = (string) ($customer['email'] ?? '');
    $_SESSION['customer_provider'] = (string) ($customer['auth_provider'] ?? 'email');
    $_SESSION['customer_avatar'] = (string) ($customer['avatar_url'] ?? '');
}

function customerLogout(): void
{
    unset($_SESSION['customer_id'], $_SESSION['customer_name'], $_SESSION['customer_email'], $_SESSION['customer_provider'], $_SESSION['customer_avatar']);
}
