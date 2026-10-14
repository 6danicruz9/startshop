<?php
declare(strict_types=1);

if (!function_exists('loadEnv')) {
    function loadEnv(string $path): void
    {
        if (!is_readable($path)) {
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            if (str_starts_with($line, 'export ')) {
                $line = substr($line, 7);
            }
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }
            $name = trim($parts[0]);
            $value = trim($parts[1]);
            if ($value !== '' && ($value[0] === '"' || $value[0] === "'")) {
                $quote = $value[0];
                $end = strpos($value, $quote, 1);
                $value = $end === false ? substr($value, 1) : substr($value, 1, $end - 1);
            } else {
                $value = trim((string) preg_replace('/\s+#.*$/', '', $value));
            }
            $_ENV[$name] = $value;
            putenv("{$name}={$value}");
        }
    }
}

loadEnv(dirname(__DIR__) . '/.env');

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }
        $v = getenv($key);
        return $v === false ? $default : $v;
    }
}

if (!function_exists('envBool')) {
    function envBool(string $key, bool $default = false): bool
    {
        $v = env($key);
        if ($v === null || $v === '') {
            return $default;
        }
        return in_array(strtolower((string) $v), ['1', 'true', 'yes', 'on'], true);
    }
}

if (!function_exists('envRequired')) {
    function envRequired(string $key): string
    {
        $v = env($key);
        if ($v === null || $v === '') {
            throw new RuntimeException("Variable de entorno requerida no definida: {$key}");
        }
        return (string) $v;
    }
}

if (!function_exists('isProduction')) {
    function isProduction(): bool
    {
        return strtolower((string) env('APP_ENV', 'production')) === 'production';
    }
}

if (!function_exists('isDebug')) {
    function isDebug(): bool
    {
        return !isProduction() && envBool('APP_DEBUG', false);
    }
}