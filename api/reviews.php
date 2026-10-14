<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/conexion.php';
$pdo = Database::getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['product_id'])) {
    $pdo->prepare("INSERT INTO ss_reviews (product_id, author_name, rating, title, comment, photos_json) VALUES (?, ?, ?, ?, ?, '[]')")
        ->execute([(int)$_POST['product_id'], trim((string)$_POST['author_name']), (int)$_POST['rating'], trim((string)$_POST['title']), trim((string)$_POST['comment'])]);
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));