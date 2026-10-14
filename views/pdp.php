<?php
declare(strict_types=1);
$stmt = $pdo->prepare("SELECT * FROM ss_products WHERE slug = ?");
$stmt->execute([(string)($_GET['slug'] ?? '')]);
$product = $stmt->fetch();
if (!$product) { require __DIR__ . '/404.php'; return; }
$price = $product['sale_price'] !== null ? (float)$product['sale_price'] : (float)$product['base_price'];
$reviews = $pdo->prepare("SELECT * FROM ss_reviews WHERE product_id = ? ORDER BY id DESC");
$reviews->execute([$product['id']]);
$revList = $reviews->fetchAll();
?>
<main class="ss-Container">
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:3rem;">
    <div style="border:1px solid var(--border-subtle);aspect-ratio:3/4;overflow:hidden;">
      <img src="<?= e($product['main_image']) ?>" style="width:100%;height:100%;object-fit:cover;">
    </div>
    <div>
      <span style="font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);"><?= e($product['brand']) ?> — <?= e($product['sku']) ?></span>
      <h1 style="font-family:var(--font-display);font-size:2.2rem;margin:8px 0 16px;"><?= e(localizedField($product, 'name')) ?></h1>
      <div style="font-size:1.5rem;font-weight:700;margin-bottom:20px;"><?= formatPrice($price) ?></div>
      <p style="color:var(--text-secondary);line-height:1.6;margin-bottom:24px;"><?= e(localizedField($product, 'description')) ?></p>
      <form data-form="add-to-cart" style="display:flex;gap:12px;">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
        <input type="number" name="quantity" value="1" min="1" class="ss-SearchInput" style="width:90px;">
        <button type="submit" class="ss-Btn" style="flex:1;"><?= e(__('product.add_to_cart')) ?></button>
      </form>
    </div>
  </div>
</main>