<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/conexion.php';
require_once dirname(__DIR__) . '/config/security.php';
$pdo = Database::getConnection();
$in = json_decode((string)file_get_contents('php://input'), true) ?: [];
$pid = (int)($in['product_id'] ?? 0);
$sid = session_id();
$stmt = $pdo->prepare("SELECT id FROM ss_wishlist WHERE session_id = ? AND product_id = ?");
$stmt->execute([$sid, $pid]);
$row = $stmt->fetch();
if ($row) {
    $pdo->prepare("DELETE FROM ss_wishlist WHERE id = ?")->execute([$row['id']]);
    echo json_encode(['ok' => true, 'in_wishlist' => false]);
} else {
    $pdo->prepare("INSERT INTO ss_wishlist (session_id, product_id) VALUES (?, ?)")->execute([$sid, $pid]);
    echo json_encode(['ok' => true, 'in_wishlist' => true]);
}