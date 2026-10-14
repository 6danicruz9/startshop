<div class="container px-4 px-lg-5 my-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-lg mt-5">
                <div class="card-header bg-dark text-white text-center rounded-top">
                    <h3 class="font-weight-light my-3">Crear una cuenta</h3>
                </div>
                <div class="card-body p-4">
                    <form action="procesar_registro.php" method="POST">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="inputNombre" name="nombre" type="text" placeholder="Tu nombre" required />
                            <label for="inputNombre">Nombre completo</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="inputEmail" name="email" type="email" placeholder="nombre@ejemplo.com" required />
                            <label for="inputEmail">Correo electrónico</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Crea una contraseña" required />
                            <label for="inputPassword">Contraseña</label>
                        </div>
                        <div class="mt-4 mb-0">
                            <button class="btn btn-dark w-100 py-2" type="submit">Registrarse</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <div class="small"><a href="index.php?p=login" class="text-decoration-none text-dark fw-bold">¿Ya tienes una cuenta? Inicia sesión</a></div>
                </div>
            </div>
        </div>
    </div>
</div>