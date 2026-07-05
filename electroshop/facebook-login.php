<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/services.php';
require_once __DIR__ . '/includes/customer-auth.php';

if (!isFacebookLoginConfigured()) {
    header('Location: login.php?message=' . urlencode('Facebook Login chưa được cấu hình App ID/App Secret.'));
    exit;
}

header('Location: ' . buildFacebookLoginUrl());
exit;