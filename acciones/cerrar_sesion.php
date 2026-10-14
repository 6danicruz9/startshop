<?php
session_start();
session_destroy(); // Borramos todos los datos de la sesión
header("Location: index.php"); // Lo enviamos de vuelta a la portada
exit();
?>