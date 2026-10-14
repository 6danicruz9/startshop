<?php
// Conectamos a la base de datos
require_once 'conexion.php';

// Comprobamos si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos los datos del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. SEGURIDAD: Encriptar la contraseña
    $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

    // 2. SEGURIDAD: Consultas preparadas para evitar Inyección SQL
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
    
    // Las "sss" significan que le pasamos 3 variables de tipo String (texto)
    $stmt->bind_param("sss", $nombre, $email, $password_encriptada);

    // Ejecutamos la consulta y comprobamos si ha ido bien
    if ($stmt->execute()) {
        // Éxito: Mostramos alerta y llevamos al usuario al login
        echo "<script>
                alert('¡Registro completado con éxito! Ya puedes iniciar sesión.');
                window.location.href = 'index.php?p=login';
              </script>";
    } else {
        // Error: Normalmente pasa si el correo ya existe (lo pusimos UNIQUE en la base de datos)
        echo "<script>
                alert('Error: Ese correo electrónico ya está registrado.');
                window.location.href = 'index.php?p=registro';
              </script>";
    }

    // Cerramos la conexión
    $stmt->close();
    $conexion->close();
} else {
    // Si alguien intenta entrar a este archivo directamente sin pasar por el formulario, lo echamos a la página principal
    header("Location: index.php");
    exit();
}
?>