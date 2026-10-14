<?php
declare(strict_types=1);
$cart = $_SESSION['cart'] ?? [];
$sub = 0.0; foreach ($cart as $i) $sub += (float)$i['unit_price'] * (int)$i['quantity'];
$doneOrder = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cart)) {
    $orderNum = 'SS-' . date('Ymd') . '-' . rand(1000, 9999);
    $addr = json_encode(['name' => $_POST['recipient_name'] ?? '', 'line1' => $_POST['line1'] ?? '', 'city' => $_POST['city'] ?? '']);
    $pdo->prepare("INSERT INTO ss_orders (order_number, guest_email, status, subtotal, grand_total, shipping_address_json, idempotency_key, tracking_number) VALUES (?, ?, 'paid', ?, ?, ?, ?, ?)")
        ->execute([$orderNum, $_POST['email'] ?? '', $sub, $sub, $addr, bin2hex(random_bytes(12)), 'TRK' . rand(100000, 999999)]);
    $_SESSION['cart'] = [];
    $doneOrder = $orderNum;
}
?>
<main class="ss-Container">
  <?php if ($doneOrder): ?>
    <div style="padding:40px;background:var(--bg-elevated);border:1px solid var(--border-strong);text-align:center;">
      <h1 style="font-family:var(--font-display);">¡Pedido Confirmado!</h1>
      <p>Tu número de orden es: <strong><?= e($doneOrder) ?></strong></p>
      <a href="index.php" class="ss-Btn">Volver a la tienda</a>
    </div>
  <?php else: ?>
    <h1 style="font-family:var(--font-display);"><?= e(__('checkout.title')) ?></h1>
    <form method="POST" style="max-width:560px;display:grid;gap:14px;background:var(--bg-elevated);padding:28px;border:1px solid var(--border-subtle);">
      <input type="email" name="email" required placeholder="Email de confirmación" class="ss-SearchInput">
      <input type="text" name="recipient_name" required placeholder="Nombre completo" class="ss-SearchInput">
      <input type="text" name="line1" required placeholder="Dirección de entrega" class="ss-SearchInput">
      <input type="text" name="city" required placeholder="Ciudad y Código Postal" class="ss-SearchInput">
      <button type="submit" class="ss-Btn">Pagar Ahora — <?= formatPrice($sub) ?></button>
    </form>
  <?php endif; ?>
</main>