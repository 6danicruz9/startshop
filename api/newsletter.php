<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/conexion.php';
$pdo = Database::getConnection();
if (!empty($_POST['email'])) {
    $pdo->prepare("INSERT IGNORE INTO ss_newsletter (email) VALUES (?)")->execute([strtolower(trim((string)$_POST['email']))]);
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));