<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/conexion.php';
require_once dirname(__DIR__) . '/includes/Mailer.php';

$pdo = Database::getConnection();
$rawPayload = (string)file_get_contents('php://input');
$sigHeader  = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
$webhookSecret = (string)env('STRIPE_WEBHOOK_SECRET', '');

$event = json_decode($rawPayload, true);
if (!is_array($event) || empty($event['id'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid payload']);
    exit;
}

// Verificación de idempotencia de eventos Webhook
$stmt = $pdo->prepare("SELECT id FROM ss_webhook_events WHERE event_id = ?");
$stmt->execute([$event['id']]);
if ($stmt->fetch()) {
    echo json_encode(['ok' => true, 'idempotent_skip' => true]);
    exit;
}

$pdo->prepare("INSERT INTO ss_webhook_events (event_id, provider, payload) VALUES (?, 'stripe', ?)")
    ->execute([$event['id'], $rawPayload]);

$type = $event['type'] ?? '';
$object = $event['data']['object'] ?? [];

if ($type === 'payment_intent.succeeded') {
    $intentId = $object['id'] ?? '';
    $orderNumber = $object['metadata']['order_number'] ?? '';
    if ($orderNumber !== '') {
        $upd = $pdo->prepare("UPDATE ss_orders SET status = 'paid', payment_intent_id = ? WHERE order_number = ?");
        $upd->execute([$intentId, $orderNumber]);
    }
} elseif ($type === 'charge.refunded') {
    $intentId = $object['payment_intent'] ?? '';
    if ($intentId !== '') {
        $upd = $pdo->prepare("UPDATE ss_orders SET status = 'refunded' WHERE payment_intent_id = ?");
        $upd->execute([$intentId]);
    }
}

echo json_encode(['ok' => true]);