<?php
declare(strict_types=1);
$sub = 0.0;
if (!empty($_SESSION['cart'])) { foreach ($_SESSION['cart'] as $i) $sub += (float)$i['unit_price'] * (int)$i['quantity']; }
?>
<div id="ss-drawer-backdrop" class="ss-DrawerBackdrop"></div>
<aside id="ss-cart-drawer" class="ss-Drawer">
  <div style="padding:20px;border-bottom:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
    <h2 style="margin:0;font-family:var(--font-display);font-size:1.1rem;"><?= e(__('cart.title')) ?></h2>
    <button type="button" data-action="close-cart" style="background:none;border:0;color:var(--text-primary);cursor:pointer;">CERRAR [X]</button>
  </div>
  <div id="ss-drawer-body" style="flex:1;overflow-y:auto;padding:0 20px;">
    <?php if (empty($_SESSION['cart'])): ?>
      <div style="padding:32px;text-align:center;color:var(--text-muted);"><?= e(__('cart.empty')) ?></div>
    <?php else: foreach ($_SESSION['cart'] as $i): ?>
      <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid var(--border-subtle);align-items:center;">
        <img src="<?= e($i['image']) ?>" style="width:55px;height:70px;object-fit:cover;">
        <div style="flex:1;">
          <div style="font-size:13px;font-weight:600;"><?= e($i['name']) ?></div>
          <div style="font-size:12px;"><?= (int)$i['quantity'] ?> x <?= formatPrice((float)$i['unit_price']) ?></div>
        </div>
        <button type="button" data-cart-remove="<?= e($i['key']) ?>" style="background:none;border:0;cursor:pointer;color:var(--status-danger);">✕</button>
      </div>
    <?php endforeach; endif; ?>
  </div>
  <div style="padding:20px;border-top:1px solid var(--border-subtle);background:var(--bg-subtle);">
    <div style="display:flex;justify-content:space-between;margin-bottom:12px;font-weight:700;">
      <span><?= e(__('cart.subtotal')) ?></span>
      <span id="ss-drawer-subtotal"><?= formatPrice($sub) ?></span>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
      <a href="?route=cart" class="ss-Btn ss-Btn--outline">Ver Bolsa</a>
      <a href="?route=checkout" class="ss-Btn"><?= e(__('cart.checkout')) ?></a>
    </div>
  </div>
</aside>