<?php
declare(strict_types=1);

if (!function_exists('loadEnv')) {
    function loadEnv(string $path): void {
        if (!is_readable($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $name = trim($parts[0]);
                $value = trim($parts[1], " \t\n\r\0\x0B\"'");
                $_ENV[$name] = $value;
                putenv("$name=$value");
            }
        }
    }
}
loadEnv(dirname(__DIR__) . '/.env');
if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
}