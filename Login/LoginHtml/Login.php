<?php
require_once '../../phpessentials/sesion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['codigo_CAM']) && isset($_POST['correo'])) {
        $codigo_CAM = trim($_POST['codigo_CAM']);
        $correo = trim($_POST['correo']);

        if (empty($codigo_CAM) || empty($correo)) {
            $loginFallido = true;
        } else {
            $sentencia = $conexion->prepare("SELECT * FROM usuario WHERE codigo_CAM = ? AND correo = ?");
            $sentencia->bind_param("is", $codigo_CAM, $correo);

            $sentencia->execute();
            $resultado = $sentencia->get_result();

            if ($resultado->num_rows > 0) {

                $usuario = $resultado->fetch_assoc();
                
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['Nombre'];

                header("Location: http://localhost/HACKATHON/ProductoTiendaIvan/PHP/TiendaV2.php");
                exit;
            } else {
                $loginFallido = true;
            }

            $sentencia->close();
        }
    }

    /*
    else if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $nie = isset($_POST['nie']) ? trim($_POST['nie']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';

        if (empty($nombre) || empty($nie) || empty($password) || empty($email)) {
            echo "<p style='color:red;'>Rellene todos los campos del formulario de registro.</p>";
        } else {
            $asunto = "Confirmacion de registro";
            $mensaje = "Hola $nombre,\n\nGracias por registrarse. Nos pondremos en contacto con usted tras revisar su informacion";
            $cabecera = "no-reply@hilan.com";

            if (mail($email, $asunto, $mensaje, $cabecera)) {
                echo "<p style='color:blue;'>Registro completado! Nos pondremos en contacto tras revisar sus datos a traves de: $email.</p>";
            } else {
                echo "<p style='color:red;'>Fallo en el envio de email! Vuelva a intentarlo.</p>";
            }
        }
    }
    */
}

// Close the connection
$conexion->close();
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
                <input type="submit" value="Log In" class="forms_buttons-action">
              </div>
              <?php if ($loginFailed): ?>
                <p id="failMessageLogin" class="failMessage">¡El usuario o la contraseña están incorrectos!</p>
              <?php endif; ?>
            </form>
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
              <a href="#" class="footer-link">Política de privacidad</a>
          </div>
          <div class="col-md-2">
              <a href="#" class="footer-link">Términos y condiciones</a>
          </div>
          <div class="col-md-2">
              <a href="#" class="footer-link">Ayuda</a>
          </div>
        </div>
        <div class="d-flex justify-content-center">
            <a class="navbar-brand" href="#">
                <img src="../../img/4-removebg-preview (1).png" alt="Logo" style="width: 100px; height: 50px; display: block;">
            </a>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script>
      const signupButton = document.getElementById("signup-button"),
            loginButton = document.getElementById("login-button"),
            userForms = document.getElementById("user_options-forms");

      signupButton.addEventListener("click", () => {
        userForms.classList.remove("bounceRight");
        userForms.classList.add("bounceLeft");
      });

      loginButton.addEventListener("click", () => {
        userForms.classList.remove("bounceLeft");
        userForms.classList.add("bounceRight");
      });

      const inputUser = document.getElementById("codigo_CAM");
      const inputPassword = document.getElementById("correo");

      function failLogin() {
        document.getElementById("failMessageLogin").style.display = "block";
      }

      function unFailLogin() {
        document.getElementById("failMessageLogin").style.display = "none";
      }

      inputUser.addEventListener("input", unFailLogin);
      inputPassword.addEventListener("input", unFailLogin);
    </script>
  </body>
</html>
