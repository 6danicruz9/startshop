<?php
declare(strict_types=1);
$cart = $_SESSION['cart'] ?? [];
$sub = 0.0; foreach ($cart as $i) $sub += (float)$i['unit_price'] * (int)$i['quantity'];
?>
<main class="ss-Container">
  <h1 style="font-family:var(--font-display);"><?= e(__('cart.title')) ?></h1>
  <?php if (empty($cart)): ?>
    <p><?= e(__('cart.empty')) ?></p>
    <a href="?route=catalog" class="ss-Btn">Ir al Catálogo</a>
  <?php else: ?>
    <div style="background:var(--bg-elevated);border:1px solid var(--border-subtle);padding:24px;margin-bottom:24px;">
      <?php foreach ($cart as $i): ?>
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border-subtle);">
          <span><?= (int)$i['quantity'] ?>x <?= e($i['name']) ?></span>
          <strong><?= formatPrice((float)$i['unit_price'] * (int)$i['quantity']) ?></strong>
        </div>
      <?php endforeach; ?>
      <div style="display:flex;justify-content:space-between;padding-top:16px;font-size:1.2rem;font-weight:700;">
        <span>Total</span><span><?= formatPrice($sub) ?></span>
      </div>
    </div>
    <a href="?route=checkout" class="ss-Btn"><?= e(__('cart.checkout')) ?></a>
  <?php endif; ?>
</main>