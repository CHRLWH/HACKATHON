<?php
    include '../phpessentials/sesion.php';
    include '../phpessentials/login_usuario.php';
    include '../phpessentials/login_admin.php';
    include '../phpessentials/registro_usuario.php';
?>


<!Doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hilan-Login</title>
        <link rel="icon" type="image/x-icon" href="../img/1-2feccb09.ico">
        <link href="LoginCss/style.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
        <div id="cookieModal" class="modal" style="display:none;">
            <div class="modal-content">
                <p>🍪 Usamos cookies para mejorar tu experiencia. ¿Aceptas el uso de cookies? Lea nuestra <a href="../../Footer/Terminos.html">Politica de privacidad</a> y 
                <a href="../../Footer/Terminos.html">Terminos y condiciones</a> para mas información
                </p>
                <button id="acceptCookiesBtn">Aceptar</button>
                <button id="declineCookiesBtn">Rechazar</button>
            </div>
        </div>

        <style>
            .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            margin-top: 20%;
            }

            /* Contenido del modal */
            .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            }
        </style>
    </head>

    <body>
        <header class="fondoDiv">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container">
                    <a class="navbar-brand" href="javascript:history.back()">
                        <img src="../img/4-removebg-preview (1).png" alt="Hilan Logo" width="200" height="100">
                    </a>
                </div>
            </nav>
        </header>

        <hr class="barraEspaciadora">

        <section class="user">
            <div class="user_options-container">
                <div class="user_options-text">
                    <div class="user_options-unregistered">
                        <h2 class="user_unregistered-title">No estas registrado?</h2>
                        <p class="user_unregistered-text">Solicita acceso pulsando en el boton de abajo</p>
                        <button class="user_unregistered-signup" id="signup-button">Solicitar acceso</button>
                    </div>

                    <div class="user_options-registered">
                        <h2 class="user_registered-title">Tienes cuenta?</h2>
                        <p class="user_registered-text">Inicia sesion usando el codigo proporcionado, nombre de usuario y contraseña</p>
                        <button class="user_registered-login" id="login-button">Iniciar sesion</button>
                    </div>
                </div>

                <div class="user_options-forms" id="user_options-forms">
                    <div class="user_forms-login">
                        <h2 class="forms_title">Login</h2>

                        <form class="forms_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" target="_self">
                            <fieldset class="forms_fieldset">
                                <div class="forms_field">
                                    <label for="codigo_CAM">Código</label>
                                    <input type="text" placeholder="Código" class="forms_field-input" name="codigo_CAM" id="codigo_CAM" required autofocus />
                                </div>

                                <div class="forms_field">
                                    <label for="correo">Correo</label>
                                    <input type="email" placeholder="Correo" class="forms_field-input" name="correo" id="correo" required />
                                </div>
                            </fieldset>

                            <div class="forms_buttons">
                                <button type="button" class="forms_buttons-forgot">
                                    Codigo de acceso olvidado?
                                </button>

                                <input type="submit" value="Login" class="forms_buttons-action">
                            </div>

                            <?php if ($loginFallido): ?>
                                <p id="failMessageLogin" class="failMessage">¡El código o correo son incorrectos!</p>
                            <?php endif; ?>
                        </form>
                        <button id="admin-login-button" value= "Log In" class="forms_buttons-action"> administración</button>
                    </div>

                    <div class="user_forms-admin" style="display: none;">
                        <h2 class="forms_title">Admin Login</h2>
                        <form class="forms_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" target="_self">
                            <fieldset class="forms_fieldset">
                                <div class="forms_field">
                                    <label for="admin_username">Usuario Admin</label>
                                    <input type="text" placeholder="Usuario Admin" class="forms_field-input" name="admin_username" id="admin_username" required />
                                </div>

                                <div class="forms_field">
                                    <label for="admin_password">Contraseña Admin</label>
                                    <input type="password" placeholder="Contraseña Admin" class="forms_field-input" name="admin_password" id="admin_password" required />
                                </div>
                            </fieldset>

                            <div class="forms_buttons">
                                <input type="submit" value="Login Admin" class="forms_buttons-action">
                            </div>
                        </form>

                        <button id="user-login-button" class="forms_buttons-action">Usuarios</button>

                        <?php if ($adminLoginFallido): ?>
                            <p id="failMessageAdmin" class="failMessage">¡Usuario o contraseña de administrador incorrectos!</p>
                        <?php endif; ?>
                    </div>

                    <div class="user_forms-signup">
                        <h2 class="forms_title">Registro</h2>

                        <form method="post" action="">
                            <input type="hidden" name="action" value="register">
                            <fieldset class="forms_fieldset">
                                <div class="forms_field">
                                    <input type="text" name="nombre" placeholder="Nombre completo" class="forms_field-input" required />
                                </div>

                                <div class="forms_field">
                                    <input type="text" name="nie" placeholder="NIE" class="forms_field-input" required />
                                </div>

                                <div class="forms_field">
                                    <input type="password" name="password" placeholder="Contraseña" class="forms_field-input" required />
                                </div>

                                <div class="forms_field">
                                    <input type="email" name="email" placeholder="Email" class="forms_field-input" required />
                                </div>
                            </fieldset>

                            <div class="forms_buttons">
                                <input type="submit" value="Solicitar acceso" class="forms_buttons-action">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <hr class="barraEspaciadora">

        <footer class="py-5 fondoDiv">
            <hr class="invisible">
            <div class="container text-center">
                <div class="row g-4 mb-5 justify-content-center">
                    <div class="col-md-2">
                        <a href="../privacidad.html" class="footer-link">Política de privacidad</a>
                    </div>

                    <div class="col-md-2">
                        <a href="../Footer/Terminos.html" class="footer-link">Términos y condiciones</a>
                    </div>

                    <div class="col-md-2">
                        <a href="../QyA.html" class="footer-link">QyA</a>
                    </div>
                </div>

                <div class="d-flex justify-content-around align-items-center">
                    <img src="../img/logomadrid.jpg" alt="Logo Madrid" class="img-fluid" style="max-height: 100px;">
                    
                    <img src="../img/4-removebg-preview (1).png" alt="Hilan Logo" class="img-fluid" style="max-height: 80px;">
                    
                    <img src="../img/logouax.png" alt="Logo UAX" class="img-fluid" style="max-height: 100px;">
                </div>
            </div>
        </footer>
  
        <script>
            document.addEventListener('DOMContentLoaded', function () {
            // Verificar si ya se ha aceptado las cookies
            if (!localStorage.getItem('cookiesAccepted')) {
                // Si no, mostrar el modal de cookies
                document.getElementById('cookieModal').style.display = 'block';
            }

            // Manejar la acción de aceptar cookies
            document.getElementById('acceptCookiesBtn').addEventListener('click', function () {
                // Almacenar que el usuario aceptó las cookies
                localStorage.setItem('cookiesAccepted', 'true');
                // Cerrar el modal
                document.getElementById('cookieModal').style.display = 'none';
            });

            // Manejar la acción de rechazar cookies
            document.getElementById('declineCookiesBtn').addEventListener('click', function () {
                // Puedes hacer que si el usuario rechaza las cookies, no hagas nada o muestres un mensaje
                // Aquí no almacenamos nada, por lo que el popup aparecerá nuevamente si el usuario recarga la página
                document.getElementById('cookieModal').style.display = 'none';
            });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

        <script src="Loginjs/Loginjs.js"></script>
    </body>
</html>
