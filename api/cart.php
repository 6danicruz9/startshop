<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/conexion.php';
require_once dirname(__DIR__) . '/config/i18n.php';
require_once dirname(__DIR__) . '/config/currency.php';
require_once dirname(__DIR__) . '/config/security.php';

$pdo = Database::getConnection();
$in = json_decode((string)file_get_contents('php://input'), true) ?: $_POST;
$action = (string)($in['action'] ?? 'get');
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($action === 'add') {
    $pid = (int)($in['product_id'] ?? 0);
    $vid = (int)($in['variant_id'] ?? 0);
    $qty = max(1, (int)($in['quantity'] ?? 1));
    $stmt = $pdo->prepare("SELECT * FROM ss_products WHERE id = ?");
    $stmt->execute([$pid]);
    $p = $stmt->fetch();
    if ($p) {
        $unit = $p['sale_price'] !== null ? (float)$p['sale_price'] : (float)$p['base_price'];
        $key = "{$pid}_{$vid}";
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] += $qty;
        } else {
            $_SESSION['cart'][$key] = [
                'key' => $key, 'product_id' => $pid, 'variant_id' => $vid ?: null,
                'slug' => $p['slug'], 'name' => localizedField($p, 'name'), 'brand' => $p['brand'],
                'variant_label' => 'Estándar', 'unit_price' => $unit, 'image' => $p['main_image'], 'quantity' => $qty
            ];
        }
    }
} elseif ($action === 'delta') {
    $k = (string)($in['item_key'] ?? '');
    if (isset($_SESSION['cart'][$k])) {
        $_SESSION['cart'][$k]['quantity'] += (int)($in['delta'] ?? 0);
        if ($_SESSION['cart'][$k]['quantity'] <= 0) unset($_SESSION['cart'][$k]);
    }
} elseif ($action === 'remove') {
    unset($_SESSION['cart'][(string)($in['item_key'] ?? '')]);
}

$total = 0; $sub = 0.0; $html = '';
foreach ($_SESSION['cart'] as $i) {
    $total += (int)$i['quantity'];
    $line = (float)$i['unit_price'] * (int)$i['quantity'];
    $sub += $line;
    $html .= '<div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid var(--border-subtle);align-items:center;">'
          . '<img src="'.e($i['image']).'" style="width:55px;height:70px;object-fit:cover;">'
          . '<div style="flex:1;"><div style="font-size:13px;font-weight:600;">'.e($i['name']).'</div>'
          . '<div style="font-size:12px;">'.(int)$i['quantity'].' x '.formatPrice((float)$i['unit_price']).'</div></div>'
          . '<button type="button" data-cart-remove="'.e($i['key']).'" style="background:none;border:0;cursor:pointer;color:var(--status-danger);">✕</button></div>';
}
if ($html === '') $html = '<div style="padding:32px;text-align:center;color:var(--text-muted);">'.e(__('cart.empty')).'</div>';
echo json_encode(['ok' => true, 'total_items' => $total, 'subtotal_formatted' => formatPrice($sub), 'drawer_html' => $html]);