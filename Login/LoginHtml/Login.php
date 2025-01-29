<?php
require_once '../../phpessentials/sesion.php';

// Inicializar variables para evitar advertencias
$loginFallido = false;
$adminLoginFallido = false;
$registroExitoso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Login de usuarios normales
    if (isset($_POST['codigo_CAM'], $_POST['correo'])) {
        $codigo_CAM = filter_var(trim($_POST['codigo_CAM']), FILTER_SANITIZE_NUMBER_INT);
        $correo = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);

        if (empty($codigo_CAM) || empty($correo)) {
            $loginFallido = true;
        } else {
            $sentencia = $conexion->prepare("SELECT * FROM usuario WHERE codigo_CAM = ? AND correo = ?");
            $sentencia->bind_param("is", $codigo_CAM, $correo);

            if ($sentencia->execute()) {
                $resultado = $sentencia->get_result();

                if ($resultado->num_rows > 0) {
                    $usuario = $resultado->fetch_assoc();
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_name'] = htmlspecialchars($usuario['Nombre'], ENT_QUOTES, 'UTF-8');

                    header("Location: http://localhost/HACKATHON/Login/LoginHtml/Login.phps");
                    exit;
                } else {
                    $loginFallido = true;
                }
                $resultado->close();
            } else {
                echo "<p style='color:red;'>Error al ejecutar la consulta de usuario.</p>";
            }
            $sentencia->close();
        }
    }

    elseif (isset($_POST['admin_username'], $_POST['admin_password'])) {
      // Obtén y limpia los datos del formulario
      $admin_username = filter_var(trim($_POST['admin_username']), FILTER_SANITIZE_STRING);
      $admin_password = trim($_POST['admin_password']);
  
      if (empty($admin_username) || empty($admin_password)) {
          $adminLoginFallido = true; // Marca el login como fallido si los campos están vacíos
      } else {
          // Consulta SQL para verificar el usuario y la contraseña en la tabla `administradores`
          $sentencia = $conexion->prepare("SELECT * FROM administradores WHERE nombre = ? AND contrasena = ?");
          $sentencia->bind_param("ss", $admin_username, $admin_password);
  
          if ($sentencia->execute()) {
              $resultado = $sentencia->get_result();
  
              if ($resultado->num_rows > 0) {
                  // El administrador existe, iniciar sesión
                  $admin = $resultado->fetch_assoc();
                  $_SESSION['admin_id'] = $admin['id'];
                  $_SESSION['admin_name'] = htmlspecialchars($admin['nombre'], ENT_QUOTES, 'UTF-8');
  
                  // Redirige al dashboard de administrador
                  header("Location: http://localhost/HACKATHON/administracion/adminPanel.php");
                  exit;
              } else {
                  $adminLoginFallido = true; // Usuario o contraseña incorrectos
              }
              $resultado->close();
          } else {
              echo "<p style='color:red;'>Error al ejecutar la consulta de administrador.</p>";
          }
          $sentencia->close();
      }
  }
  

    // Registro de nuevos usuarios
    elseif (isset($_POST['action']) && $_POST['action'] === 'register') {
        $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
        $nie = filter_var(trim($_POST['nie']), FILTER_SANITIZE_STRING);
        $password = trim($_POST['password']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

        if (empty($nombre) || empty($nie) || empty($password) || empty($email)) {
            echo "<p style='color:red;'>Rellene todos los campos del formulario de registro.</p>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p style='color:red;'>El formato del correo no es válido.</p>";
        } else {
            $asunto = "Confirmación de registro";
            $mensaje = "Hola $nombre,\n\nGracias por registrarse. Nos pondremos en contacto con usted tras revisar su información.";
            $cabecera = "From: no-reply@hilan.com\r\nContent-Type: text/plain; charset=UTF-8";

            if (mail($email, $asunto, $mensaje, $cabecera)) {
                echo "<p style='color:blue;'>Registro completado! Nos pondremos en contacto tras revisar sus datos a través de: $email.</p>";
                $registroExitoso = true;
            } else {
                echo "<p style='color:red;'>Fallo en el envío de email! Vuelva a intentarlo.</p>";
            }
        }
    }
}

// Cerrar conexión
if (isset($conexion)) {
    $conexion->close();
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hilan-Login</title>
    <link rel="icon" type="image/x-icon" href="../../img/1-2feccb09.ico">
    <link href="../LoginCss/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .user_forms-admin {
        position: absolute;
        top: 70px;
        left: 40px;
        width: calc(100% - 80px);
      }

      #admin-login-button,
      #user-login-button {
        margin-top: 20px;
        width: 100%;
      }
    </style>
  </head>
  <body>

    <div class="fondoDiv"><img src="../../img/4-removebg-preview (1).png" width="200" height="100"></div>

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
                <button type="button" class="forms_buttons-forgot">Contraseña olvidada?</button>
                <button type="button" class="forms_buttons-forgot">Codigo de acceso olvidado?</button>
                <input type="submit" value="Login Admin" class="forms_buttons-action">
              </div>
              <?php if ($loginFallido): ?>
                <p id="failMessageLogin" class="failMessage">¡El código o correo están incorrectos!</p>
              <?php endif; ?>

            </form>
            <button id="admin-login-button" value= "Log In" class="forms_buttons-action"> Log In</button>
          </div>

          <div class="user_forms-admin" style="display: none;">
            <h2 class="forms_title">Admin Login</h2>
            <form class="forms_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" target="_self">
              <fieldset class="forms_fieldset">
                <div class="forms_field">
                  <label for="admin_username" >Usuario Admin</label>
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
            <button id="user-login-button" class="forms_buttons-action">Login</button>
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
              <a href="../../privacidad.html" class="footer-link">Política de privacidad</a>
          </div>
          <div class="col-md-2">
              <a href="../../Terminos.html" class="footer-link">Términos y condiciones</a>
          </div>
          <div class="col-md-2">
              <a href="../../QyA.html" class="footer-link">QyA</a>
          </div>
        </div>
        <div class="d-flex justify-content-around align-items-center">
            <img src="../../img/logomadrid.jpg" alt="Logo Madrid" class="img-fluid" style="max-height: 100px;">
            
            <img src="../../img/4-removebg-preview (1).png" alt="Hilan Logo" class="img-fluid" style="max-height: 80px;">
            
            <img src="../../img/logouax.png" alt="Logo UAX" class="img-fluid" style="max-height: 100px;">
        </div>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
   
   <script>
     const signupButton = document.getElementById("signup-button"),
      loginButton = document.getElementById("login-button"),
      userForms = document.getElementById("user_options-forms"),
      adminLoginButton = document.getElementById("admin-login-button"),
      userLoginButton = document.getElementById("user-login-button"),
      userFormsLogin = document.querySelector(".user_forms-login"),
      userFormsAdmin = document.querySelector(".user_forms-admin"),
      userFormsSignup = document.querySelector(".user_forms-signup")

    function showLoginForm() {
      userFormsLogin.style.display = "block"
      userFormsAdmin.style.display = "none"
      userFormsSignup.style.display = "none"
    }

    function showAdminForm() {
      userFormsLogin.style.display = "none"
      userFormsAdmin.style.display = "block"
      userFormsSignup.style.display = "none"
    }

    function showSignupForm() {
      userFormsLogin.style.display = "none"
      userFormsAdmin.style.display = "none"
      userFormsSignup.style.display = "block"
    }

    signupButton.addEventListener("click", () => {
      userForms.classList.remove("bounceRight")
      userForms.classList.add("bounceLeft")
      showSignupForm()
    })

    loginButton.addEventListener("click", () => {
      userForms.classList.remove("bounceLeft")
      userForms.classList.add("bounceRight")
      showLoginForm()
    })

    adminLoginButton.addEventListener("click", showAdminForm)
    userLoginButton.addEventListener("click", showLoginForm)

    const inputUser = document.getElementById("codigo_CAM")
    const inputPassword = document.getElementById("correo")

    function failLogin() {
      document.getElementById("failMessageLogin").style.display = "block"
    }

    function unFailLogin() {
      document.getElementById("failMessageLogin").style.display = "none"
    }

    inputUser.addEventListener("input", unFailLogin)
    inputPassword.addEventListener("input", unFailLogin)
    </script>
  </body>
</html>
