<?php
declare(strict_types=1);
$featured = $pdo->query("SELECT * FROM ss_products ORDER BY id DESC LIMIT 8")->fetchAll();
$categories = $pdo->query("SELECT * FROM ss_categories ORDER BY id ASC LIMIT 4")->fetchAll();
?>
<main>
  <section class="ss-Hero">
    <div>
      <span class="ss-HeroEyebrow"><?= e(__('hero.eyebrow')) ?></span>
      <h1 class="ss-HeroTitle"><?= e(__('hero.title')) ?></h1>
      <p style="font-size:1.05rem;color:var(--text-secondary);margin-bottom:2rem;"><?= e(__('hero.subtitle')) ?></p>
      <div style="display:flex;gap:14px;">
        <a href="?route=catalog" class="ss-Btn"><?= e(__('hero.cta_primary')) ?></a>
        <a href="?route=cms&page=about" class="ss-Btn ss-Btn--outline"><?= e(__('hero.cta_secondary')) ?></a>
      </div>
    </div>
    <div class="ss-HeroMedia">
      <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1000&q=85" alt="Hero">
    </div>
  </section>

  <section class="ss-Container">
    <h2 style="font-family:var(--font-display);font-size:1.8rem;margin-bottom:1.5rem;">Categorías de Colección</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem;">
      <?php foreach ($categories as $cat): ?>
        <a href="?route=catalog&category=<?= e($cat['slug']) ?>" style="position:relative;aspect-ratio:4/5;overflow:hidden;border:1px solid var(--border-subtle);display:flex;align-items:flex-end;padding:20px;color:#fff;text-decoration:none;background:#111;">
          <img src="<?= e($cat['image_url']) ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.75;">
          <h3 style="position:relative;z-index:2;font-family:var(--font-display);font-size:1.35rem;margin:0;"><?= e(localizedField($cat, 'name')) ?></h3>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="ss-Container">
    <h2 style="font-family:var(--font-display);font-size:1.8rem;margin-bottom:1.5rem;">Lanzamientos Destacados</h2>
    <div class="ss-ProductGrid">
      <?php foreach ($featured as $prod):
        $price = $prod['sale_price'] !== null ? (float)$prod['sale_price'] : (float)$prod['base_price'];
      ?>
        <article class="ss-ProductCard">
          <div class="ss-ProductThumb">
            <?php if ($prod['sale_price'] !== null): ?><span class="ss-Badge ss-Badge--sale">OFERTA</span><?php endif; ?>
            <button type="button" class="ss-WishlistBtn" data-action="toggle-wishlist" data-product-id="<?= (int)$prod['id'] ?>">♥</button>
            <a href="?route=product&slug=<?= e($prod['slug']) ?>"><img src="<?= e($prod['main_image']) ?>" alt="<?= e(localizedField($prod, 'name')) ?>"></a>
          </div>
          <div class="ss-ProductBody">
            <span class="ss-ProductBrand"><?= e($prod['brand']) ?></span>
            <a href="?route=product&slug=<?= e($prod['slug']) ?>" class="ss-ProductTitle"><?= e(localizedField($prod, 'name')) ?></a>
            <div style="font-weight:700;margin-top:6px;"><?= formatPrice($price) ?></div>
            <form data-form="add-to-cart" style="margin-top:10px;">
              <input type="hidden" name="product_id" value="<?= (int)$prod['id'] ?>">
              <input type="hidden" name="quantity" value="1">
              <button type="submit" class="ss-Btn" style="width:100%;padding:0.6rem;"><?= e(__('product.add_to_cart')) ?></button>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>