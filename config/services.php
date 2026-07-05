<?php

function getElectroshopBaseUrl(): string
{
    $configuredUrl = rtrim((string) getenv('LTWT6_APP_URL'), '/');
    if ($configuredUrl !== '') {
        return $configuredUrl;
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ((int) ($_SERVER['SERVER_PORT'] ?? 80) === 443);
    $scheme = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    return $scheme . '://' . $host . '/LTWT6/electroshop';
}

function getFacebookConfig(): array
{
    return [
        'app_id' => (string) getenv('LTWT6_FB_APP_ID'),
        'app_secret' => (string) getenv('LTWT6_FB_APP_SECRET'),
        'redirect_uri' => (string) (getenv('LTWT6_FB_REDIRECT_URI') ?: getElectroshopBaseUrl() . '/facebook-callback.php'),
    ];
}

function isFacebookLoginConfigured(): bool
{
    $config = getFacebookConfig();

    return $config['app_id'] !== '' && $config['app_secret'] !== '';
}

function buildFacebookLoginUrl(): string
{
    $config = getFacebookConfig();
    $state = bin2hex(random_bytes(16));
    $_SESSION['fb_oauth_state'] = $state;

    return 'https://www.facebook.com/v20.0/dialog/oauth?' . http_build_query([
        'client_id' => $config['app_id'],
        'redirect_uri' => $config['redirect_uri'],
        'state' => $state,
        'scope' => 'email,public_profile',
        'response_type' => 'code',
    ]);
}

function sendJsonRequest(string $method, string $url, array $payload = []): array
{
    $curl = curl_init();
    $body = '';

    if ($method === 'GET' && !empty($payload)) {
        $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($payload);
    } elseif (!empty($payload)) {
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 20,
    ]);

    if ($body !== '') {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    }

    $responseBody = curl_exec($curl);
    $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($responseBody === false || $curlError !== '') {
        throw new RuntimeException('Không thể kết nối dịch vụ bên ngoài: ' . $curlError);
    }

    return [
        'status' => $httpCode,
        'body' => json_decode($responseBody, true),
        'raw' => $responseBody,
    ];
}

function fetchFacebookUserFromCode(string $code): array
{
    $config = getFacebookConfig();

    $tokenResponse = sendJsonRequest('GET', 'https://graph.facebook.com/v20.0/oauth/access_token', [
        'client_id' => $config['app_id'],
        'redirect_uri' => $config['redirect_uri'],
        'client_secret' => $config['app_secret'],
        'code' => $code,
    ]);

    $accessToken = (string) ($tokenResponse['body']['access_token'] ?? '');
    if ($accessToken === '') {
        throw new RuntimeException('Không lấy được access token từ Facebook.');
    }

    $userResponse = sendJsonRequest('GET', 'https://graph.facebook.com/me', [
        'fields' => 'id,name,email,picture.width(300).height(300)',
        'access_token' => $accessToken,
    ]);

    $user = $userResponse['body'] ?? [];
    if (empty($user['id'])) {
        throw new RuntimeException('Không lấy được thông tin người dùng Facebook.');
    }

    return $user;
}

function getMoMoConfig(): array
{
    return [
        'endpoint' => (string) (getenv('LTWT6_MOMO_ENDPOINT') ?: 'https://test-payment.momo.vn/v2/gateway/api/create'),
        'partner_code' => (string) getenv('LTWT6_MOMO_PARTNER_CODE'),
        'access_key' => (string) getenv('LTWT6_MOMO_ACCESS_KEY'),
        'secret_key' => (string) getenv('LTWT6_MOMO_SECRET_KEY'),
        'redirect_url' => (string) (getenv('LTWT6_MOMO_REDIRECT_URL') ?: getElectroshopBaseUrl() . '/momo-return.php'),
        'ipn_url' => (string) (getenv('LTWT6_MOMO_NOTIFY_URL') ?: getElectroshopBaseUrl() . '/momo-notify.php'),
    ];
}

function isMomoConfigured(): bool
{
    $config = getMoMoConfig();

    return $config['partner_code'] !== '' && $config['access_key'] !== '' && $config['secret_key'] !== '';
}

function createMomoPayment(int $orderId, float $amount): array
{
    $config = getMoMoConfig();
    $requestId = 'MOMO-' . $orderId . '-' . time();
    $momoOrderId = 'LTWT6-' . $orderId . '-' . time();
    $extraData = base64_encode(json_encode(['order_id' => $orderId], JSON_UNESCAPED_UNICODE));
    $orderInfo = 'Thanh toán đơn hàng #' . $orderId;

    $rawSignature = 'accessKey=' . $config['access_key']
        . '&amount=' . (int) round($amount)
        . '&extraData=' . $extraData
        . '&ipnUrl=' . $config['ipn_url']
        . '&orderId=' . $momoOrderId
        . '&orderInfo=' . $orderInfo
        . '&partnerCode=' . $config['partner_code']
        . '&redirectUrl=' . $config['redirect_url']
        . '&requestId=' . $requestId
        . '&requestType=captureWallet';

    $payload = [
        'partnerCode' => $config['partner_code'],
        'partnerName' => 'LTWT6 ElectroShop',
        'storeId' => 'LTWT6Store',
        'requestId' => $requestId,
        'amount' => (string) (int) round($amount),
        'orderId' => $momoOrderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $config['redirect_url'],
        'ipnUrl' => $config['ipn_url'],
        'lang' => 'vi',
        'requestType' => 'captureWallet',
        'autoCapture' => true,
        'extraData' => $extraData,
        'signature' => hash_hmac('sha256', $rawSignature, $config['secret_key']),
    ];

    $response = sendJsonRequest('POST', $config['endpoint'], $payload);
    $body = $response['body'] ?? [];

    if ((int) ($body['resultCode'] ?? -1) !== 0) {
        throw new RuntimeException((string) ($body['message'] ?? 'Không tạo được thanh toán MoMo.'));
    }

    return $body;
}

function extractOrderIdFromMoMoExtraData(?string $extraData = null): int
{
    $decoded = json_decode((string) base64_decode((string) $extraData, true), true);
    if (!is_array($decoded)) {
        return 0;
    }

    return (int) ($decoded['order_id'] ?? 0);
}