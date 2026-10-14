<?php
declare(strict_types=1);
$where = ["1=1"]; $params = [];
if (!empty($_GET['category'])) { $where[] = "c.slug = ?"; $params[] = $_GET['category']; }
if (!empty($_GET['brand'])) { $where[] = "p.brand = ?"; $params[] = $_GET['brand']; }
$sql = "SELECT p.* FROM ss_products p LEFT JOIN ss_categories c ON c.id = p.category_id WHERE " . implode(' AND ', $where) . " ORDER BY p.id DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$products = $stmt->fetchAll();
$cats = $pdo->query("SELECT * FROM ss_categories")->fetchAll();
?>
<main class="ss-Container">
  <h1 style="font-family:var(--font-display);font-size:2.2rem;margin-bottom:1.5rem;"><?= e(__('nav.catalog')) ?></h1>
  <div style="display:flex;gap:10px;margin-bottom:2rem;flex-wrap:wrap;">
    <a href="?route=catalog" class="ss-Btn ss-Btn--outline" style="padding:0.45rem 0.9rem;">Todos</a>
    <?php foreach ($cats as $c): ?>
      <a href="?route=catalog&category=<?= e($c['slug']) ?>" class="ss-Btn ss-Btn--outline" style="padding:0.45rem 0.9rem;"><?= e(localizedField($c, 'name')) ?></a>
    <?php endforeach; ?>
  </div>
  <div class="ss-ProductGrid">
    <?php foreach ($products as $prod):
      $price = $prod['sale_price'] !== null ? (float)$prod['sale_price'] : (float)$prod['base_price'];
    ?>
      <article class="ss-ProductCard">
        <div class="ss-ProductThumb">
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
</main>