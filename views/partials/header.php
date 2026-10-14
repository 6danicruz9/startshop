<?php
declare(strict_types=1);
$cartCount = 0;
if (!empty($_SESSION['cart'])) { foreach ($_SESSION['cart'] as $ci) $cartCount += (int)$ci['quantity']; }
$currentUser = Auth::user($pdo);
?>
<!DOCTYPE html>
<html lang="<?= e($currentLocale) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
  <?= Seo::renderMeta($pageMeta ?? []) ?>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/design-system.css">
</head>
<body>
  <div class="ss-Announcement">
    <span>ENVÍO EXPRESS GRATUITO +150€ — CUPÓN 10%: ATELIER10</span>
    <div style="display:flex;gap:12px;">
      <a href="?lang=es" style="color:inherit;">ES</a>
      <a href="?lang=en" style="color:inherit;">EN</a>
      <span>|</span>
      <a href="?currency=EUR" style="color:inherit;">EUR</a>
      <a href="?currency=USD" style="color:inherit;">USD</a>
    </div>
  </div>
  <header class="ss-Header">
    <div class="ss-NavContainer">
      <a href="index.php" class="ss-Brand">STARTSHOP <span style="font-size:0.72rem;font-family:var(--font-mono);font-weight:400;">ATELIER</span></a>
      <nav>
        <ul class="ss-NavLinks">
          <li><a class="ss-NavLink" href="?route=home"><?= e(__('nav.home')) ?></a></li>
          <li><a class="ss-NavLink" href="?route=catalog"><?= e(__('nav.catalog')) ?></a></li>
          <li><a class="ss-NavLink" href="?route=cms&page=about"><?= e(__('nav.about')) ?></a></li>
          <li><a class="ss-NavLink" href="?route=cms&page=faq"><?= e(__('nav.faq')) ?></a></li>
          <li><a class="ss-NavLink" href="?route=cms&page=contact"><?= e(__('nav.contact')) ?></a></li>
        </ul>
      </nav>
      <div style="display:flex;align-items:center;gap:12px;">
        <div class="ss-SearchWrap">
          <input type="search" id="ss-live-search" class="ss-SearchInput" placeholder="<?= e(__('search.placeholder')) ?>">
          <div id="ss-search-dropdown" class="ss-SearchDropdown"></div>
        </div>
        <button type="button" data-action="toggle-theme" class="ss-Btn ss-Btn--outline" style="padding:0.45rem 0.75rem;font-size:0.7rem;">TEMA</button>
        <a href="?route=account" class="ss-NavLink" style="font-family:var(--font-mono);font-size:0.78rem;"><?= $currentUser ? e($currentUser['full_name']) : e(__('nav.account')) ?></a>
        <a href="admin/index.php" class="ss-NavLink" style="font-family:var(--font-mono);font-size:0.75rem;color:var(--accent-highlight);">ADMIN</a>
        <button type="button" data-action="open-cart" class="ss-Btn" style="padding:0.5rem 0.9rem;font-size:0.75rem;">BOLSA (<span data-cart-count><?= $cartCount ?></span>)</button>
      </div>
    </div>
  </header>