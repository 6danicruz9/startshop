<?php
declare(strict_types=1);
class Seo {
    public static function renderMeta(array $meta = []): string {
        $t = e(($meta['title'] ?? 'Tienda Oficial') . ' — STARTSHOP ATELIER');
        return "<title>{$t}</title><meta name=\"description\" content=\"Comercio electrónico StartShop Atelier\">";
    }
    public static function renderOrganizationJsonLd(): string { return ''; }
    public static function renderProductJsonLd(array $p): string { return ''; }
    public static function renderBreadcrumbsJsonLd(array $items): string { return ''; }
}