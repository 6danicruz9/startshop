<?php
declare(strict_types=1);
$currencies = [
    'EUR' => ['symbol' => '€', 'rate' => 1.0000, 'position' => 'after'],
    'USD' => ['symbol' => '$', 'rate' => 1.0850, 'position' => 'before'],
    'GBP' => ['symbol' => '£', 'rate' => 0.8550, 'position' => 'before'],
];
if (isset($_GET['currency']) && isset($currencies[$_GET['currency']])) {
    $_SESSION['currency'] = $_GET['currency'];
}
$currentCurrency = $_SESSION['currency'] ?? 'EUR';

function formatPrice(float $amountInEur, ?string $currencyCode = null): string {
    global $currencies, $currentCurrency;
    $code = $currencyCode ?? $currentCurrency;
    $conf = $currencies[$code] ?? $currencies['EUR'];
    $formatted = number_format(round($amountInEur * $conf['rate'], 2), 2, ',', '.');
    return $conf['position'] === 'before' ? $conf['symbol'] . $formatted : $formatted . ' ' . $conf['symbol'];
}
function getCurrencyRate(?string $currencyCode = null): float {
    global $currencies, $currentCurrency;
    $code = $currencyCode ?? $currentCurrency;
    return (float) ($currencies[$code]['rate'] ?? 1.0);
}