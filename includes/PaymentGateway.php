<?php
declare(strict_types=1);
class PaymentGateway {
    public static function createStripeIntent(float $amount, string $currency, string $orderNumber, string $idempotencyKey): array {
        $intentId = 'pi_' . substr(hash('sha256', $idempotencyKey), 0, 20);
        return ['ok' => true, 'intent_id' => $intentId, 'status' => 'paid'];
    }
    public static function refundOrder(PDO $pdo, int $orderId): array {
        $pdo->prepare("UPDATE ss_orders SET status = 'refunded' WHERE id = ?")->execute([$orderId]);
        return ['ok' => true];
    }
}