<?php
declare(strict_types=1);

$featuredStmt = $pdo->query("SELECT * FROM ss_products WHERE is_featured = 1 ORDER BY id DESC LIMIT 8");
$featuredProducts = $featuredStmt->fetchAll();

$categoriesStmt = $pdo->query("SELECT * FROM ss_categories WHERE featured = 1 ORDER BY id ASC LIMIT 4");
$categories = $categoriesStmt->fetchAll();

$wishIds = [];
$wStmt = $pdo->prepare("SELECT product_id FROM ss_wishlist WHERE session_id = ?");
$wStmt->execute([session_id()]);
foreach ($wStmt->fetchAll() as $w) {
    $wishIds[] = (int)$w['product_id'];
}
?>
<main>
  <!-- Hero Principal -->
  <section class="ss-Hero">
    <div class="reveal-up">
      <span class="ss-HeroEyebrow"><?= e(__('hero.eyebrow')) ?></span>
      <h1 class="ss-HeroTitle"><?= e(__('hero.title')) ?></h1>
      <p style="font-size:1.08rem;color:var(--text-secondary);max-width:540px;line-height:1.65;margin-bottom:var(--space-8);">
        <?= e(__('hero.subtitle')) ?>
      </p>
      <div style="display:flex;gap:16px;flex-wrap:wrap;">
        <a href="?route=catalog" class="ss-Btn"><?= e(__('hero.cta_primary')) ?></a>
        <a href="?route=cms&page=blog" class="ss-Btn ss-Btn--outline"><?= e(__('hero.cta_secondary')) ?></a>
      </div>

      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:var(--space-12);padding-top:var(--space-6);border-top:1px solid var(--border-subtle);">
        <div>
          <div style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;">48H</div>
          <div style="font-size:0.78rem;color:var(--text-muted);">Entrega Express Global</div>
        </div>
        <div>
          <div style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;">100%</div>
          <div style="font-size:0.78rem;color:var(--text-muted);">Trazabilidad de Materiales</div>
        </div>
        <div>
          <div style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;">4.9/5</div>
          <div style="font-size:0.78rem;color:var(--text-muted);">Clientes Verificados</div>
        </div>
      </div>
    </div>

    <div class="ss-HeroMedia reveal-up">
      <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1200&q=85" alt="Colección StartShop Atelier">
    </div>
  </section>

  <!-- Categorías Destacadas -->
  <section class="ss-Container">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-8);">
      <div>
        <span style="font-family:var(--font-mono);font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);">ARQUITECTURA DE COLECCIÓN</span>
        <h2 style="font-family:var(--font-display);font-size:2rem;margin:6px 0 0;">Categorías Principales</h2>
      </div>
      <a href="?route=catalog" class="ss-NavLink">Ver Todas →</a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:var(--space-6);">
      <?php foreach ($categories as $cat): ?>
        <a href="?route=catalog&category=<?= e($cat['slug']) ?>" class="reveal-up" style="position:relative;aspect-ratio:4/5;overflow:hidden;border:1px solid var(--border-subtle);text-decoration:none;color:#fff;display:flex;align-items:flex-end;padding:24px;background:#111;">
          <img src="<?= e($cat['image_url']) ?>" alt="<?= e(localizedField($cat, 'name')) ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.78;transition:transform 0.7s ease;">
          <div style="position:relative;z-index:2;">
            <div style="font-family:var(--font-mono);font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">EXPLORAR</div>
            <h3 style="font-family:var(--font-display);font-size:1.5rem;margin:4px 0 0;"><?= e(localizedField($cat, 'name')) ?></h3>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Productos Destacados -->
  <section class="ss-Container">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-8);">
      <div>
        <span style="font-family:var(--font-mono);font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);">SELECCIÓN CURADA</span>
        <h2 style="font-family:var(--font-display);font-size:2rem;margin:6px 0 0;">Lanzamientos Destacados</h2>
      </div>
    </div>

    <div class="ss-ProductGrid">
      <?php foreach ($featuredProducts as $prod):
        $hasSale = $prod['sale_price'] !== null;
        $finalPrice = $hasSale ? (float)$prod['sale_price'] : (float)$prod['base_price'];
        $inWish = in_array((int)$prod['id'], $wishIds, true);
      ?>
        <article class="ss-ProductCard reveal-up">
          <div class="ss-ProductThumb">
            <?php if ($hasSale): ?>
              <span class="ss-Badge ss-Badge--sale">OFERTA</span>
            <?php elseif ((int)$prod['is_new'] === 1): ?>
              <span class="ss-Badge">NUEVO</span>
            <?php endif; ?>

            <button type="button" class="ss-WishlistBtn <?= $inWish ? 'is-active' : '' ?>" data-action="toggle-wishlist" data-product-id="<?= (int)$prod['id'] ?>" aria-label="Guardar en lista de deseos">
              ♥
            </button>

            <a href="?route=product&slug=<?= e($prod['slug']) ?>">
              <img src="<?= e($prod['main_image']) ?>" alt="<?= e(localizedField($prod, 'name')) ?>" loading="lazy">
            </a>
          </div>

          <div class="ss-ProductBody">
            <span class="ss-ProductBrand"><?= e($prod['brand']) ?></span>
            <a href="?route=product&slug=<?= e($prod['slug']) ?>" class="ss-ProductTitle"><?= e(localizedField($prod, 'name')) ?></a>
            <div style="display:flex;align-items:center;gap:10px;margin-top:auto;padding-top:10px;">
              <span style="font-weight:700;font-size:0.95rem;"><?= formatPrice($finalPrice) ?></span>
              <?php if ($hasSale): ?>
                <span style="text-decoration:line-through;color:var(--text-muted);font-size:0.82rem;"><?= formatPrice((float)$prod['base_price']) ?></span>
              <?php endif; ?>
            </div>

            <form data-form="add-to-cart" style="margin-top:12px;">
              <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="product_id" value="<?= (int)$prod['id'] ?>">
              <input type="hidden" name="quantity" value="1">
              <button type="submit" class="ss-Btn" style="width:100%;padding:0.65rem;" <?= ((int)$prod['stock'] <= 0) ? 'disabled' : '' ?>>
                <?= ((int)$prod['stock'] > 0) ? e(__('product.add_to_cart')) : e(__('product.out_of_stock')) ?>
              </button>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Banner Editorial & Social Proof -->
  <section class="ss-Container">
    <div class="reveal-up" style="background:var(--bg-elevated);border:1px solid var(--border-strong);padding:var(--space-12);display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:var(--space-8);align-items:center;">
      <div>
        <span style="font-family:var(--font-mono);font-size:0.75rem;color:var(--accent-highlight);">GARANTÍA DE ATELIER</span>
        <h3 style="font-family:var(--font-display);font-size:2rem;margin:8px 0 16px;">Construido para resistir el paso del tiempo</h3>
        <p style="color:var(--text-secondary);line-height:1.6;margin:0 0 24px;">
          Cada pieza de nuestra colección se somete a controles de calidad de grado industrial. Devoluciones gratuitas durante 30 días y soporte directo de taller.
        </p>
        <a href="?route=cms&page=about" class="ss-Btn ss-Btn--outline">Conocer el Manifiesto</a>
      </div>
      <div style="display:grid;gap:16px;">
        <blockquote style="margin:0;padding:20px;background:var(--bg-subtle);border-left:3px solid var(--accent-highlight);">
          <p style="margin:0 0 8px;font-size:0.92rem;">"La calidad del tejido técnico y la precisión del corte superan a marcas que triplican su precio. Envío recibido en 24 horas."</p>
          <footer style="font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);">— Alejandro V., Compra Verificada</footer>
        </blockquote>
        <blockquote style="margin:0;padding:20px;background:var(--bg-subtle);border-left:3px solid var(--border-strong);">
          <p style="margin:0 0 8px;font-size:0.92rem;">"Experiencia de compra impecable, empaquetado minimalista reciclable y atención al detalle sobresaliente."</p>
          <footer style="font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);">— Elena M., Compra Verificada</footer>
        </blockquote>
      </div>
    </div>
  </section>
</main>