<?php
declare(strict_types=1);

require_once __DIR__ . '/env.php';

date_default_timezone_set((string) env('APP_TIMEZONE', 'Europe/Madrid'));

$ssLogDir = dirname(__DIR__) . '/storage/logs';
if (!is_dir($ssLogDir)) {
    @mkdir($ssLogDir, 0755, true);
}

error_reporting(E_ALL);
ini_set('display_errors', isDebug() ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', $ssLogDir . '/php-error.log');

if (!function_exists('isHttps')) {
    function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    }
}

if (!function_exists('clientIp')) {
    function clientIp(): string
    {
        $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }
}

if (!function_exists('appUrl')) {
    function appUrl(string $path = ''): string
    {
        return rtrim((string) env('APP_URL', ''), '/') . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_trans_sid', '0');
    ini_set('session.gc_maxlifetime', '7200');
    session_name('SSSESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => isHttps(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

set_exception_handler(static function (Throwable $e): void {
    error_log(sprintf('[%s] %s in %s:%d', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()));
    if (!headers_sent()) {
        http_response_code(500);
    }
    $uri = (string) ($_SERVER['REQUEST_URI'] ?? '');
    if (str_contains($uri, '/api/') || str_contains((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json')) {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode(['ok' => false, 'error' => isDebug() ? $e->getMessage() : 'Error interno.']);
        return;
    }
    $view = dirname(__DIR__) . '/views/500.php';
    if (is_readable($view)) {
        require $view;
    } else {
        echo 'Error interno.';
    }
});