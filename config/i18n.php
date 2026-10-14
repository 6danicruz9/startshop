<?php
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) session_start();

$supportedLocales = ['es', 'en'];
if (isset($_GET['lang']) && in_array($_GET['lang'], $supportedLocales, true)) {
    $_SESSION['locale'] = $_GET['lang'];
}
$currentLocale = $_SESSION['locale'] ?? 'es';

$translations = [
    'es' => [
        'nav.home' => 'Inicio', 'nav.catalog' => 'Colección', 'nav.editorial' => 'Editorial',
        'nav.about' => 'Atelier', 'nav.faq' => 'FAQ', 'nav.contact' => 'Contacto', 'nav.account' => 'Mi Cuenta',
        'hero.eyebrow' => 'EDICIÓN CÁPSULA OTOÑO / INVIERNO 2026',
        'hero.title' => 'INGENIERÍA TEXTIL Y DISEÑO ATEMPORAL',
        'hero.subtitle' => 'Prendas técnicas, calzado arquitectónico y objetos de precisión diseñados para durar décadas.',
        'hero.cta_primary' => 'Explorar Colección', 'hero.cta_secondary' => 'Ver Lookbook',
        'search.placeholder' => 'Buscar por prenda, marca o SKU...',
        'filter.title' => 'Refinar Selección', 'filter.price' => 'Rango de Precio', 'filter.category' => 'Categoría',
        'filter.brand' => 'Marca', 'filter.size' => 'Talla', 'filter.color' => 'Color', 'filter.rating' => 'Valoración', 'filter.stock' => 'Solo en stock',
        'product.add_to_cart' => 'Añadir a la bolsa', 'product.out_of_stock' => 'Agotado',
        'product.reviews' => 'Reseñas verificadas', 'product.related' => 'Completar el conjunto',
        'cart.title' => 'Tu Bolsa de Compra', 'cart.empty' => 'Tu bolsa está vacía en este momento.',
        'cart.subtotal' => 'Subtotal', 'cart.shipping' => 'Envío estimado', 'cart.tax' => 'Impuestos incluidos', 'cart.total' => 'Total', 'cart.checkout' => 'Tramitar Pedido',
        'checkout.title' => 'Finalizar Compra Segura',
        'footer.newsletter_title' => 'Únete al Círculo Privado', 'footer.newsletter_sub' => 'Acceso anticipado a lanzamientos limitados.'
    ],
    'en' => [
        'nav.home' => 'Home', 'nav.catalog' => 'Collection', 'nav.editorial' => 'Editorial',
        'nav.about' => 'Atelier', 'nav.faq' => 'FAQ', 'nav.contact' => 'Contact', 'nav.account' => 'Account',
        'hero.eyebrow' => 'AUTUMN / WINTER 2026 CAPSULE EDITION',
        'hero.title' => 'TEXTILE ENGINEERING & TIMELESS DESIGN',
        'hero.subtitle' => 'Technical garments, architectural footwear and precision objects engineered to last decades.',
        'hero.cta_primary' => 'Explore Collection', 'hero.cta_secondary' => 'View Lookbook',
        'search.placeholder' => 'Search garment, brand or SKU...',
        'filter.title' => 'Refine Selection', 'filter.price' => 'Price Range', 'filter.category' => 'Category',
        'filter.brand' => 'Brand', 'filter.size' => 'Size', 'filter.color' => 'Color', 'filter.rating' => 'Rating', 'filter.stock' => 'In stock only',
        'product.add_to_cart' => 'Add to Bag', 'product.out_of_stock' => 'Sold Out',
        'product.reviews' => 'Verified Reviews', 'product.related' => 'Complete the Look',
        'cart.title' => 'Your Shopping Bag', 'cart.empty' => 'Your shopping bag is currently empty.',
        'cart.subtotal' => 'Subtotal', 'cart.shipping' => 'Estimated Shipping', 'cart.tax' => 'Taxes included', 'cart.total' => 'Total', 'cart.checkout' => 'Proceed to Checkout',
        'checkout.title' => 'Secure Checkout',
        'footer.newsletter_title' => 'Join the Private Circle', 'footer.newsletter_sub' => 'Early access to limited drops.'
    ]
];

function __(string $key): string {
    global $translations, $currentLocale;
    return $translations[$currentLocale][$key] ?? $translations['es'][$key] ?? $key;
}
function localizedField(array $row, string $baseField): string {
    global $currentLocale;
    return (string) ($row["{$baseField}_{$currentLocale}"] ?? $row["{$baseField}_es"] ?? $row[$baseField] ?? '');
}