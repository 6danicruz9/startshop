<?php
declare(strict_types=1);

$where = ["1=1"];
$params = [];

$catSlug = trim((string)($_GET['category'] ?? ''));
if ($catSlug !== '') {
    $where[] = "c.slug = ?";
    $params[] = $catSlug;
}

$brand = trim((string)($_GET['brand'] ?? ''));
if ($brand !== '') {
    $where[] = "p.brand = ?";
    $params[] = $brand;
}

$minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
if ($minPrice !== null) {
    $where[] = "COALESCE(p.sale_price, p.base_price) >= ?";
    $params[] = $minPrice;
}

$maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
if ($maxPrice !== null) {
    $where[] = "COALESCE(p.sale_price, p.base_price) <= ?";
    $params[] = $maxPrice;
}

$size = trim((string)($_GET['size'] ?? ''));
if ($size !== '') {
    $where[] = "EXISTS (SELECT 1 FROM ss_product_variants v WHERE v.product_id = p.id AND v.size = ? AND v.stock > 0)";
    $params[] = $size;
}

$color = trim((string)($_GET['color'] ?? ''));
if ($color !== '') {
    $where[] = "EXISTS (SELECT 1 FROM ss_product_variants v WHERE v.product_id = p.id AND v.color_name = ?)";
    $params[] = $color;
}

$minRating = isset($_GET['rating']) && $_GET['rating'] !== '' ? (float)$_GET['rating'] : null;
if ($minRating !== null) {
    $where[] = "p.rating_avg >= ?";
    $params[] = $minRating;
}

if (!empty($_GET['in_stock'])) {
    $where[] = "p.stock > 0";
}

$sort = (string)($_GET['sort'] ?? 'newest');
$orderBy = match ($sort) {
    'price_asc'  => 'COALESCE(p.sale_price, p.base_price) ASC',
    'price_desc' => 'COALESCE(p.sale_price, p.base_price) DESC',
    'rating'     => 'p.rating_avg DESC',
    default      => 'p.id DESC'
};

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 9;
$offset = ($page - 1) * $perPage;

$whereSql = implode(' AND ', $where);

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM ss_products p LEFT JOIN ss_categories c ON c.id = p.category_id WHERE {$whereSql}");
$countStmt->execute($params);
$totalRows = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($totalRows / $perPage));

$sql = "SELECT p.*, c.slug AS cat_slug, c.name_es AS cat_name_es, c.name_en AS cat_name_en
        FROM ss_products p
        LEFT JOIN ss_categories c ON c.id = p.category_id
        WHERE {$whereSql}
        ORDER BY {$orderBy}
        LIMIT {$perPage} OFFSET {$offset}";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$allCats = $pdo->query("SELECT slug, name_es, name_en FROM ss_categories ORDER BY name_es ASC")->fetchAll();
$allBrands = $pdo->query("SELECT DISTINCT brand FROM ss_products ORDER BY brand ASC")->fetchAll(PDO::FETCH_COLUMN);
$allSizes = $pdo->query("SELECT DISTINCT size FROM ss_product_variants ORDER BY size ASC")->fetchAll(PDO::FETCH_COLUMN);
$allColors = $pdo->query("SELECT DISTINCT color_name FROM ss_product_variants ORDER BY color_name ASC")->fetchAll(PDO::FETCH_COLUMN);
?>
<main class="ss-Container">
  <div style="display:flex;justify-content:space-between;align-items:baseline;border-bottom:1px solid var(--border-subtle);padding-bottom:16px;margin-bottom:32px;flex-wrap:wrap;gap:16px;">
    <div>
      <h1 style="font-family:var(--font-display);font-size:2.2rem;margin:0;"><?= e(__('nav.catalog')) ?></h1>
      <p style="color:var(--text-muted);font-size:0.85rem;margin:4px 0 0;"><?= $totalRows ?> piezas encontradas</p>
    </div>

    <form method="GET" style="display:flex;gap:12px;align-items:center;">
      <?php foreach ($_GET as $k => $v): if ($k !== 'sort' && $k !== 'page'): ?>
        <input type="hidden" name="<?= e((string)$k) ?>" value="<?= e((string)$v) ?>">
      <?php endif; endforeach; ?>
      <label for="sort-select" style="font-size:0.8rem;font-family:var(--font-mono);">ORDENAR:</label>
      <select id="sort-select" name="sort" onchange="this.form.submit()" class="ss-SearchInput" style="width:auto;">
        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Más recientes</option>
        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Precio: menor a mayor</option>
        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Precio: mayor a menor</option>
        <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Mejor valorados</option>
      </select>
    </form>
  </div>

  <div style="display:grid;grid-template-columns:260px 1fr;gap:var(--space-8);align-items:start;">
    <!-- Panel de Filtros -->
    <aside style="background:var(--bg-elevated);border:1px solid var(--border-subtle);padding:20px;">
      <form method="GET" action="index.php" style="display:grid;gap:18px;">
        <input type="hidden" name="route" value="catalog">
        <div style="font-family:var(--font-mono);font-size:0.78rem;text-transform:uppercase;letter-spacing:0.1em;border-bottom:1px solid var(--border-subtle);padding-bottom:8px;">
          <?= e(__('filter.title')) ?>
        </div>

        <div>
          <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:6px;"><?= e(__('filter.category')) ?></label>
          <select name="category" class="ss-SearchInput">
            <option value="">Todas</option>
            <?php foreach ($allCats as $c): ?>
              <option value="<?= e($c['slug']) ?>" <?= $catSlug === $c['slug'] ? 'selected' : '' ?>><?= e(localizedField($c, 'name')) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:6px;"><?= e(__('filter.brand')) ?></label>
          <select name="brand" class="ss-SearchInput">
            <option value="">Todas</option>
            <?php foreach ($allBrands as $b): ?>
              <option value="<?= e((string)$b) ?>" <?= $brand === $b ? 'selected' : '' ?>><?= e((string)$b) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:6px;"><?= e(__('filter.price')) ?> (EUR)</label>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
            <input type="number" name="min_price" placeholder="Mín" value="<?= e((string)($_GET['min_price'] ?? '')) ?>" class="ss-SearchInput">
            <input type="number" name="max_price" placeholder="Máx" value="<?= e((string)($_GET['max_price'] ?? '')) ?>" class="ss-SearchInput">
          </div>
        </div>

        <div>
          <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:6px;"><?= e(__('filter.size')) ?></label>
          <select name="size" class="ss-SearchInput">
            <option value="">Todas</option>
            <?php foreach ($allSizes as $s): ?>
              <option value="<?= e((string)$s) ?>" <?= $size === $s ? 'selected' : '' ?>><?= e((string)$s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:6px;"><?= e(__('filter.color')) ?></label>
          <select name="color" class="ss-SearchInput">
            <option value="">Todos</option>
            <?php foreach ($allColors as $col): ?>
              <option value="<?= e((string)$col) ?>" <?= $color === $col ? 'selected' : '' ?>><?= e((string)$col) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label style="font-size:0.8rem;font-weight:600;display:block;margin-bottom:6px;"><?= e(__('filter.rating')) ?></label>
          <select name="rating" class="ss-SearchInput">
            <option value="">Cualquiera</option>
            <option value="4" <?= $minRating === 4.0 ? 'selected' : '' ?>>4★ o más</option>
            <option value="4.5" <?= $minRating === 4.5 ? 'selected' : '' ?>>4.5★ o más</option>
          </select>
        </div>

        <label style="display:flex;align-items:center;gap:8px;font-size:0.84rem;cursor:pointer;">
          <input type="checkbox" name="in_stock" value="1" <?= !empty($_GET['in_stock']) ? 'checked' : '' ?>>
          <?= e(__('filter.stock')) ?>
        </label>

        <button type="submit" class="ss-Btn" style="width:100%;">Aplicar Filtros</button>
        <a href="?route=catalog" style="text-align:center;font-size:0.78rem;color:var(--text-muted);text-decoration:underline;">Limpiar filtros</a>
      </form>
    </aside>

    <!-- Listado de Productos -->
    <section>
      <?php if (empty($products)): ?>
        <div style="background:var(--bg-elevated);border:1px solid var(--border-subtle);padding:64px 24px;text-align:center;">
          <h2 style="font-family:var(--font-display);margin:0 0 8px;">Sin coincidencias exactas</h2>
          <p style="color:var(--text-secondary);margin:0 0 24px;">Prueba a ampliar el rango de precios o restablecer los filtros seleccionados.</p>
          <a href="?route=catalog" class="ss-Btn">Restablecer Catálogo</a>
        </div>
      <?php else: ?>
        <div class="ss-ProductGrid">
          <?php foreach ($products as $prod):
            $hasSale = $prod['sale_price'] !== null;
            $finalPrice = $hasSale ? (float)$prod['sale_price'] : (float)$prod['base_price'];
          ?>
            <article class="ss-ProductCard reveal-up">
              <div class="ss-ProductThumb">
                <?php if ($hasSale): ?>
                  <span class="ss-Badge ss-Badge--sale">OFERTA</span>
                <?php endif; ?>
                <a href="?route=product&slug=<?= e($prod['slug']) ?>">
                  <img src="<?= e($prod['main_image']) ?>" alt="<?= e(localizedField($prod, 'name')) ?>" loading="lazy">
                </a>
              </div>
              <div class="ss-ProductBody">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                  <span class="ss-ProductBrand"><?= e($prod['brand']) ?></span>
                  <span style="font-family:var(--font-mono);font-size:0.72rem;color:var(--text-muted);">★ <?= number_format((float)$prod['rating_avg'], 1) ?></span>
                </div>
                <a href="?route=product&slug=<?= e($prod['slug']) ?>" class="ss-ProductTitle"><?= e(localizedField($prod, 'name')) ?></a>
                <div style="display:flex;gap:10px;align-items:center;margin-top:auto;padding-top:10px;">
                  <span style="font-weight:700;"><?= formatPrice($finalPrice) ?></span>
                  <?php if ($hasSale): ?>
                    <span style="text-decoration:line-through;color:var(--text-muted);font-size:0.8rem;"><?= formatPrice((float)$prod['base_price']) ?></span>
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

        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
          <nav aria-label="Paginación de catálogo" style="display:flex;justify-content:center;gap:8px;margin-top:40px;">
            <?php for ($p = 1; $p <= $totalPages; $p++):
              $qParams = array_merge($_GET, ['page' => $p]);
            ?>
              <a href="?<?= http_build_query($qParams) ?>" class="ss-Btn <?= $p === $page ? '' : 'ss-Btn--outline' ?>" style="padding:0.5rem 0.9rem;">
                <?= $p ?>
              </a>
            <?php endfor; ?>
          </nav>
        <?php endif; ?>
      <?php endif; ?>
    </section>
  </div>
</main>