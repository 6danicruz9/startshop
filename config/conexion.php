<?php
declare(strict_types=1);

require_once __DIR__ . '/config/bootstrap.php';

mysqli_report(MYSQLI_REPORT_OFF);
$conexion = @new mysqli(
    (string) env('DB_HOST', '127.0.0.1'),
    (string) env('DB_USERNAME', 'root'),
    (string) env('DB_PASSWORD', ''),
    (string) env('DB_DATABASE', 'startshop'),
    (int) env('DB_PORT', 3306)
);
if (!$conexion->connect_error) {
    $conexion->set_charset('utf8mb4');
}

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                (string) env('DB_HOST', '127.0.0.1'),
                (int) env('DB_PORT', 3306),
                (string) env('DB_DATABASE', 'startshop')
            );
            self::$pdo = new PDO($dsn, (string) env('DB_USERNAME', 'root'), (string) env('DB_PASSWORD', ''), [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 5,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4, time_zone = '+00:00', sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",
            ]);
        }
        return self::$pdo;
    }

    public static function transaction(callable $fn): mixed
    {
        $pdo = self::getConnection();
        if ($pdo->inTransaction()) {
            return $fn($pdo);
        }
        $pdo->beginTransaction();
        try {
            $result = $fn($pdo);
            $pdo->commit();
            return $result;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
```

`storage/logs/.gitkeep`
```