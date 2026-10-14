<?php
// Archivo de conexión a la base de datos, usando MySQLi (PHP 7+)

// 1. Definir las credenciales de la base de datos
$db_host = "localhost";   // Servidor
$db_usuario = "root";     // Usuario de la BD
$db_pass = "";            // Contraseña (en XAMPP por defecto es vacía)
$db_nombre = "startshop"; // Nombre de tu base de datos

// 2. Crear la conexión
$conexion = new mysqli($db_host, $db_usuario, $db_pass, $db_nombre);

// 3. Verificar si hubo un error en la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 4. Establecer el juego de caracteres a UTF-8
if (!$conexion->set_charset("utf8mb4")) {
    die("Error al cargar el set de caracteres utf8mb4: " . $conexion->error);
}
?>