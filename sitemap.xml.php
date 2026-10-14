<?php
declare(strict_types=1);

header('Content-Type: application/xml; charset=utf-8');
require_once __DIR__ . '/conexion.php';

$pdo = Database::getConnection();
$baseUrl = rtrim((string)env('APP_URL', 'http://localhost'), '/');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc><?= htmlspecialchars($baseUrl . '/?route=home') ?></loc>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc><?= htmlspecialchars($baseUrl . '/?route=catalog') ?></loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>
  <?php
  $stmt = $pdo->query("SELECT slug, created_at FROM ss_products ORDER BY id DESC");
  foreach ($stmt->fetchAll() as $prod):
  ?>
  <url>
    <loc><?= htmlspecialchars($baseUrl . '/?route=product&slug=' . $prod['slug']) ?></loc>
    <lastmod><?= date('Y-m-d', strtotime((string)$prod['created_at'])) ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <?php endforeach; ?>
</urlset>
