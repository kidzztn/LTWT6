<?php
require_once __DIR__ . '/includes/customer-auth.php';
customerLogout();
header('Location: login.php');
exit;
