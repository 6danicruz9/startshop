<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/conexion.php';
require_once dirname(__DIR__) . '/config/currency.php';
require_once dirname(__DIR__) . '/config/security.php';
$pdo = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['op'] ?? '') === 'create_product') {
    $slug = strtolower(trim((string)preg_replace('/[^A-Za-z0-9-]+/', '-', (string)$_POST['name_es']))) . '-' . rand(10,99);
    $pdo->prepare("INSERT INTO ss_products (category_id, sku, slug, brand, name_es, name_en, short_desc_es, short_desc_en, description_es, description_en, base_price, stock, main_image) VALUES (1, ?, ?, ?, ?, ?, 'Edición Atelier', 'Atelier Edition', 'Calidad técnica.', 'Technical quality.', ?, ?, ?)")
        ->execute([$_POST['sku'],$slug, $_POST['brand'],$_POST['name_es'], $_POST['name_es'], (float)$_POST['base_price'], (int)$_POST['stock'],$_POST['main_image']]);
}
$products =$pdo->query("SELECT * FROM ss_products ORDER BY id DESC")->fetchAll();
$orders =$pdo->query("SELECT * FROM ss_orders ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
  <meta charset="UTF-8"><title>Admin — StartShop</title>
  <link rel="stylesheet" href="../css/design-system.css">
</head>
<body>
  <header class="ss-Header">
    <div class="ss-NavContainer">
      <span class="ss-Brand">STARTSHOP ADMIN</span>
      <div style="display:flex;gap:10px;">
        <a href="http://localhost/phpmyadmin/index.php?route=/database/structure&db=startshop" target="_blank" class="ss-Btn ss-Btn--outline">Abrir phpMyAdmin</a>
        <a href="../index.php" class="ss-Btn">Volver a la Tienda</a>
      </div>
    </div>
  </header>
  <main class="ss-Container">
    <form method="POST" style="background:var(--bg-elevated);padding:24px;border:1px solid var(--border-subtle);display:grid;gap:10px;margin-bottom:32px;">
      <h2 style="margin:0;font-family:var(--font-display);">Crear Nuevo Producto</h2>
      <input type="hidden" name="op" value="create_product">
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
        <input type="text" name="sku" required placeholder="SKU (ej. SS-99)" class="ss-SearchInput">
        <input type="text" name="brand" required placeholder="Marca" class="ss-SearchInput">
        <input type="text" name="name_es" required placeholder="Nombre del Producto" class="ss-SearchInput">
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr 2fr;gap:10px;">
        <input type="number" step="0.01" name="base_price" required placeholder="Precio €" class="ss-SearchInput">
        <input type="number" name="stock" required placeholder="Stock" class="ss-SearchInput">
        <input type="url" name="main_image" required placeholder="URL Imagen (https://...)" class="ss-SearchInput">
      </div>
      <button type="submit" class="ss-Btn">Guardar en MySQL</button>
    </form>

    <h2 style="font-family:var(--font-display);">Catálogo Actual (<?= count($products) ?> productos)</h2>
    <table style="width:100%;border-collapse:collapse;background:var(--bg-elevated);border:1px solid var(--border-subtle);">
      <?php foreach ($products as$p): ?>
        <tr style="border-bottom:1px solid var(--border-subtle);">
          <td style="padding:12px;font-family:var(--font-mono);"><?= e($p['sku']) ?></td>
          <td style="padding:12px;font-weight:600;"><?= e($p['name_es']) ?></td>
          <td style="padding:12px;"><?= formatPrice((float)$p['base_price']) ?></td>
          <td style="padding:12px;">Stock: <?= (int)$p['stock'] ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </main>
</body>
</html>