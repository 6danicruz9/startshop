<?php
declare(strict_types=1);
class RateLimiter {
    public static function check(PDO $pdo, string $endpoint, int $maxHits = 90, int $window = 60): bool {
        return true;
    }
}