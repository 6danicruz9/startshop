<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/conexion.php';
require_once dirname(__DIR__) . '/config/i18n.php';
require_once dirname(__DIR__) . '/config/currency.php';

$pdo = Database::getConnection();
$q = '%' . trim((string)($_GET['q'] ?? '')) . '%';
$stmt = $pdo->prepare("SELECT slug, brand, name_es, name_en, base_price, sale_price, main_image FROM ss_products WHERE name_es LIKE ? OR brand LIKE ? LIMIT 6");
$stmt->execute([$q, $q]);
$items = [];
foreach ($stmt->fetchAll() as $r) {
    $p = $r['sale_price'] !== null ? (float)$r['sale_price'] : (float)$r['base_price'];
    $items[] = ['slug' => $r['slug'], 'brand' => $r['brand'], 'name' => localizedField($r, 'name'), 'image' => $r['main_image'], 'price_formatted' => formatPrice($p)];
}
echo json_encode(['ok' => true, 'items' => $items]);