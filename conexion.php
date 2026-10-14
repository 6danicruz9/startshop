<?php
declare(strict_types=1);
require_once __DIR__ . '/config/env.php';

$servidor  = (string) env('DB_HOST', '127.0.0.1');
$puerto    = (int) env('DB_PORT', 3306);
$usuario   = (string) env('DB_USERNAME', 'root');
$password  = (string) env('DB_PASSWORD', '');
$basedatos = (string) env('DB_DATABASE', 'startshop');

$conexion = @new mysqli($servidor, $usuario, $password, $basedatos, $puerto);
if (!$conexion->connect_error) {
    $conexion->set_charset('utf8mb4');
}

class Database {
    private static ?PDO $pdo = null;
    public static function getConnection(): PDO {
        if (self::$pdo === null) {
            $host = (string) env('DB_HOST', '127.0.0.1');
            $port = (int) env('DB_PORT', 3306);
            $db   = (string) env('DB_DATABASE', 'startshop');
            $user = (string) env('DB_USERNAME', 'root');
            $pass = (string) env('DB_PASSWORD', '');
            self::$pdo = new PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$pdo;
    }
}