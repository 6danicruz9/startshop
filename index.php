<!--
  Version   : 1.0
  Autor     : Daniel de la Cruz.
  Fecha     : 05 Octubre 2026
-->
<?php
  $pagina = $_GET['p'] ?? null;
  require_once 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Mi tienda virtual" />
        <meta name="author" content="Daniel de la Cruz" />
        <title>Mi Tienda</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="index.php">Mi Tienda</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link <?php if ($pagina != 'tienda') print 'active';?>" aria-current="page" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">Nosotros</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php if ($pagina == 'tienda') print 'active';?>" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Tienda</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="index.php?p=tienda">Todos los Productos</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="#!">Artículos Populares</a></li>
                                <li><a class="dropdown-item" href="#!">Novedades</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex">
                        <button class="btn btn-outline-dark" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Carrito
                            <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        <header class="py-5" style="background-color: #f8f9fa; border-bottom: 1px solid #eaeaea;">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center">
                    <h1 class="display-5 fw-bold" style="letter-spacing: -0.05em; color: #111;">StartShop</h1>
                    <p class="lead fw-normal text-muted mb-0" style="font-size: 1.1rem;">El catálogo digital de nueva generación</p>
                </div>
            </div>
        </header>
        <!-- Sistema de rutas -->
        <?php
          switch ($pagina) {
              case null:
                  include 'product-item.html';
                  include 'products-section.html';
                  break;
              case "informacion-producto":
                  include 'product-item.html';
                  break;
              case "tienda":
                  include 'products-section.html';
                  break;
              default:
                  //include 'error-404.html';
          }
        ?>
        
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Mi Tienda 2026</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <?php
        // Cerramos la conexión al final
        $conexion->close();
        ?>
    </body>
</html>