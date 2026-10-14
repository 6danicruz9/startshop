<?php
declare(strict_types=1);
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/config/i18n.php';
require_once __DIR__ . '/config/currency.php';
require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/includes/Mailer.php';
require_once __DIR__ . '/includes/Seo.php';
require_once __DIR__ . '/includes/Auth.php';
require_once __DIR__ . '/includes/Validator.php';
require_once __DIR__ . '/includes/RateLimiter.php';
require_once __DIR__ . '/includes/PaymentGateway.php';

try {
    $pdo = Database::getConnection();
} catch (Throwable $e) {
    http_response_code(500);
    require __DIR__ . '/views/500.php';
    exit;
}

$route = (string)($_GET['route'] ?? 'home');
require __DIR__ . '/views/partials/header.php';
switch ($route) {
    case 'home':     require __DIR__ . '/views/home.php'; break;
    case 'catalog':  require __DIR__ . '/views/catalog.php'; break;
    case 'product':  require __DIR__ . '/views/pdp.php'; break;
    case 'cart':     require __DIR__ . '/views/cart.php'; break;
    case 'checkout': require __DIR__ . '/views/checkout.php'; break;
    case 'account':  require __DIR__ . '/views/account.php'; break;
    case 'cms':      require __DIR__ . '/views/cms.php'; break;
    default:         require __DIR__ . '/views/404.php'; break;
}
require __DIR__ . '/views/partials/footer.php';