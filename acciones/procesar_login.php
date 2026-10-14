<?php
// Arrancamos el sistema de sesiones (MUY IMPORTANTE, siempre va en la línea 1 o 2)
session_start();

// Conectamos a la base de datos
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Buscamos si existe un usuario con ese correo
    $stmt = $conexion->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Si encontramos exactamente 1 usuario...
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        // Comparamos la contraseña escrita con la encriptada en la base de datos
        if (password_verify($password, $usuario['password'])) {
            
            // ¡Éxito! Guardamos sus datos en variables de sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            // Lo enviamos a la página principal
            echo "<script>
                    window.location.href = 'index.php';
                  </script>";
        } else {
            // Contraseña incorrecta
            echo "<script>
                    alert('La contraseña es incorrecta.');
                    window.location.href = 'index.php?p=login';
                  </script>";
        }
    } else {
        // No existe el correo
        echo "<script>
                alert('No existe ninguna cuenta con ese correo electrónico.');
                window.location.href = 'index.php?p=login';
              </script>";
    }

    $stmt->close();
    $conexion->close();
} else {
    header("Location: index.php");
    exit();
}
?>