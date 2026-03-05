<?php
/**
 * OPcache Clear Script
 * 
 * This script provides a simple way to clear OPcache via HTTP request.
 * Should be used with caution in production environments.
 */

// 简单的安全检查 - 可以根据需要添加更强的认证
$allowedIps = [
    '127.0.0.1',
    '::1',
    'localhost'
];

$clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
$isLocalRequest = in_array($clientIp, $allowedIps) || 
                  strpos($clientIp, '192.168.') === 0 || 
                  strpos($clientIp, '10.') === 0 ||
                  preg_match('/^172\.(1[6-9]|2[0-9]|3[01])\./', $clientIp);

// 检查是否来自本地网络或通过正确的密钥访问
$secret = $_GET['secret'] ?? '';
$validSecret = hash('sha256', 'opcache_clear_' . date('Y-m-d'));

if (!$isLocalRequest && $secret !== $validSecret) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied']);
    exit;
}

$result = ['status' => 'error', 'message' => 'Unknown error'];

// 尝试清理 OPcache
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        $result = [
            'status' => 'success',
            'message' => 'OPcache cleared successfully',
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => 'opcache_reset'
        ];
    } else {
        $result = [
            'status' => 'error',
            'message' => 'Failed to clear OPcache',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
} else {
    $result = [
        'status' => 'error',
        'message' => 'OPcache is not available or not enabled',
        'timestamp' => date('Y-m-d H:i:s')
    ];
}

// 返回 JSON 响应
header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
?>
