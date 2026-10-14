<?php
declare(strict_types=1);

$slug = trim((string)($_GET['slug'] ?? ''));
$stmt = $pdo->prepare("
    SELECT p.*, c.slug AS cat_slug, c.name_es AS cat_name_es, c.name_en AS cat_name_en
    FROM ss_products p
    LEFT JOIN ss_categories c ON c.id = p.category_id
    WHERE p.slug = ?
");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    require __DIR__ . '/404.php';
    return;
}

$vStmt = $pdo->prepare("SELECT * FROM ss_product_variants WHERE product_id = ? ORDER BY id ASC");
$vStmt->execute([$product['id']]);
$variants = $vStmt->fetchAll();

$rStmt = $pdo->prepare("SELECT * FROM ss_reviews WHERE product_id = ? ORDER BY id DESC");
$rStmt->execute([$product['id']]);
$reviews = $rStmt->fetchAll();

$relStmt = $pdo->prepare("SELECT * FROM ss_products WHERE category_id = ? AND id != ? LIMIT 4");
$relStmt->execute([$product['category_id'], $product['id']]);
$related = $relStmt->fetchAll();

$gallery = json_decode((string)$product['gallery_json'], true) ?: [$product['main_image']];
$specs   = json_decode((string)$product['specs_json'], true) ?: [];
$effectivePrice = $product['sale_price'] !== null ? (float)$product['sale_price'] : (float)$product['base_price'];
?>
<?= Seo::renderProductJsonLd($product) ?>
<?= Seo::renderBreadcrumbsJsonLd([
    ['name' => __('nav.home'), 'url' => env('APP_URL') . '/?route=home'],
    ['name' => __('nav.catalog'), 'url' => env('APP_URL') . '/?route=catalog'],
    ['name' => localizedField($product, 'name'), 'url' => env('APP_URL') . '/?route=product&slug=' . $product['slug']]
]) ?>

<main class="ss-Container">
  <div style="font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);margin-bottom:24px;">
    <a href="?route=home" style="color:inherit;">INICIO</a> /
    <a href="?route=catalog&category=<?= e((string)$product['cat_slug']) ?>" style="color:inherit;"><?= e(strtoupper(localizedField($product, 'cat_name'))) ?></a> /
    <span><?= e(strtoupper(localizedField($product, 'name'))) ?></span>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:var(--space-12);align-items:start;">
    <!-- Galería con Zoom -->
    <div>
      <div data-pdp-zoom style="position:relative;aspect-ratio:3/4;overflow:hidden;border:1px solid var(--border-subtle);background:var(--bg-subtle);cursor:zoom-in;">
        <img id="ss-pdp-main-img" src="<?= e($product['main_image']) ?>" alt="<?= e(localizedField($product, 'name')) ?>" style="width:100%;height:100%;object-fit:cover;transition:transform 0.2s ease;">
      </div>
      <div style="display:flex;gap:12px;margin-top:12px;overflow-x:auto;">
        <?php foreach ($gallery as $imgUrl): ?>
          <button type="button" data-pdp-thumb="<?= e($imgUrl) ?>" style="padding:0;border:1px solid var(--border-subtle);background:none;width:76px;height:96px;cursor:pointer;">
            <img src="<?= e($imgUrl) ?>" alt="Miniatura" style="width:100%;height:100%;object-fit:cover;">
          </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Información de Producto y Compra -->
    <div>
      <div style="font-family:var(--font-mono);font-size:0.78rem;color:var(--text-muted);letter-spacing:0.12em;text-transform:uppercase;">
        <?= e($product['brand']) ?> — SKU: <?= e($product['sku']) ?>
      </div>
      <h1 style="font-family:var(--font-display);font-size:2.4rem;margin:8px 0 12px;"><?= e(localizedField($product, 'name')) ?></h1>

      <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
        <span style="font-size:1.5rem;font-weight:700;"><?= formatPrice($effectivePrice) ?></span>
        <?php if ($product['sale_price'] !== null): ?>
          <span style="text-decoration:line-through;color:var(--text-muted);"><?= formatPrice((float)$product['base_price']) ?></span>
        <?php endif; ?>
        <span style="font-family:var(--font-mono);font-size:0.78rem;color:var(--status-success);">
          ● <?= (int)$product['stock'] > 0 ? "EN STOCK ({$product['stock']} uds.)" : "AGOTADO" ?>
        </span>
      </div>

      <p style="color:var(--text-secondary);line-height:1.65;margin-bottom:24px;">
        <?= e(localizedField($product, 'description')) ?>
      </p>

      <form data-form="add-to-cart" style="display:grid;gap:16px;padding:24px;background:var(--bg-elevated);border:1px solid var(--border-subtle);">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

        <?php if (!empty($variants)): ?>
          <div>
            <label for="variant-select" style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:8px;">SELECCIONAR VARIANTE (TALLA / COLOR)</label>
            <select id="variant-select" name="variant_id" class="ss-SearchInput" required>
              <?php foreach ($variants as $v): ?>
                <option value="<?= (int)$v['id'] ?>" <?= ((int)$v['stock'] <= 0) ? 'disabled' : '' ?>>
                  Talla <?= e($v['size']) ?> — <?= e($v['color_name']) ?> (<?= (int)$v['stock'] ?> disponibles)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:100px 1fr auto;gap:12px;">
          <input type="number" name="quantity" value="1" min="1" max="<?= max(1, (int)$product['stock']) ?>" class="ss-SearchInput" aria-label="Cantidad">
          <button type="submit" class="ss-Btn" <?= ((int)$product['stock'] <= 0) ? 'disabled' : '' ?>>
            <?= ((int)$product['stock'] > 0) ? e(__('product.add_to_cart')) : e(__('product.out_of_stock')) ?>
          </button>
          <button type="button" class="ss-Btn ss-Btn--outline" data-action="toggle-wishlist" data-product-id="<?= (int)$product['id'] ?>" aria-label="Añadir a favoritos">♥</button>
        </div>
      </form>

      <?php if (!empty($specs)): ?>
        <div style="margin-top:32px;border-top:1px solid var(--border-subtle);padding-top:20px;">
          <h3 style="font-family:var(--font-mono);font-size:0.78rem;text-transform:uppercase;letter-spacing:0.12em;">Especificaciones Técnicas</h3>
          <dl style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:0.86rem;">
            <?php foreach ($specs as $k => $v): ?>
              <dt style="color:var(--text-muted);"><?= e((string)$k) ?></dt>
              <dd style="margin:0;font-weight:600;"><?= e((string)$v) ?></dd>
            <?php endforeach; ?>
          </dl>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Reseñas con fotos -->
  <section style="margin-top:var(--space-16);border-top:1px solid var(--border-subtle);padding-top:var(--space-12);">
    <h2 style="font-family:var(--font-display);font-size:1.8rem;margin:0 0 24px;">
      <?= e(__('product.reviews')) ?> (<?= count($reviews) ?>)
    </h2>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:var(--space-8);">
      <div style="display:grid;gap:16px;">
        <?php foreach ($reviews as $rev):
          $photos = json_decode((string)$rev['photos_json'], true) ?: [];
        ?>
          <article style="padding:20px;background:var(--bg-elevated);border:1px solid var(--border-subtle);">
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
              <strong><?= e($rev['author_name']) ?></strong>
              <span style="font-family:var(--font-mono);font-size:0.8rem;"><?= str_repeat('★', (int)$rev['rating']) ?></span>
            </div>
            <div style="font-weight:600;margin-bottom:6px;"><?= e($rev['title']) ?></div>
            <p style="margin:0;color:var(--text-secondary);font-size:0.88rem;line-height:1.5;"><?= e($rev['comment']) ?></p>
            <?php if (!empty($photos)): ?>
              <div style="display:flex;gap:8px;margin-top:12px;">
                <?php foreach ($photos as $pUrl): ?>
                  <img src="<?= e($pUrl) ?>" alt="Foto reseña" style="width:64px;height:64px;object-fit:cover;border:1px solid var(--border-subtle);">
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>

      <form action="api/reviews.php" method="POST" style="padding:24px;background:var(--bg-elevated);border:1px solid var(--border-subtle);display:grid;gap:12px;align-content:start;">
        <h3 style="margin:0 0 8px;font-family:var(--font-display);">Publicar Reseña</h3>
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
        <input type="text" name="author_name" required placeholder="Tu nombre" class="ss-SearchInput">
        <select name="rating" class="ss-SearchInput" required>
          <option value="5">5 Estrellas — Excelente</option>
          <option value="4">4 Estrellas — Muy bueno</option>
          <option value="3">3 Estrellas — Correcto</option>
          <option value="2">2 Estrellas — Regular</option>
          <option value="1">1 Estrella — Deficiente</option>
        </select>
        <input type="text" name="title" required placeholder="Título de tu reseña" class="ss-SearchInput">
        <input type="url" name="photo_url" placeholder="URL de foto (opcional)" class="ss-SearchInput">
        <textarea name="comment" rows="4" required placeholder="Escribe tu experiencia con la prenda..." class="ss-SearchInput"></textarea>
        <button type="submit" class="ss-Btn">Enviar Valoración</button>
      </form>
    </div>
  </section>

  <!-- Productos Relacionados -->
  <?php if (!empty($related)): ?>
    <section style="margin-top:var(--space-16);border-top:1px solid var(--border-subtle);padding-top:var(--space-12);">
      <h2 style="font-family:var(--font-display);font-size:1.8rem;margin:0 0 24px;"><?= e(__('product.related')) ?></h2>
      <div class="ss-ProductGrid">
        <?php foreach ($related as $rel): ?>
          <article class="ss-ProductCard">
            <div class="ss-ProductThumb">
              <a href="?route=product&slug=<?= e($rel['slug']) ?>">
                <img src="<?= e($rel['main_image']) ?>" alt="<?= e(localizedField($rel, 'name')) ?>" loading="lazy">
              </a>
            </div>
            <div class="ss-ProductBody">
              <span class="ss-ProductBrand"><?= e($rel['brand']) ?></span>
              <a href="?route=product&slug=<?= e($rel['slug']) ?>" class="ss-ProductTitle"><?= e(localizedField($rel, 'name')) ?></a>
              <span style="font-weight:700;margin-top:8px;"><?= formatPrice((float)($rel['sale_price'] ?? $rel['base_price'])) ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</main>