
<?php
    require_once '../../phpessentials/sesion.php';

    $codigo_CAM = trim($_POST['codigo_CAM']);
            $correo = trim($_POST['correo']);

            if (empty($codigo_CAM) || empty($correo)) {
                $loginFallido = true;
            } else {
            
                $statement = $conexion->prepare("SELECT * FROM usuario WHERE codigo_CAM = ? AND correo = ?");
                $statement->bind_param("is", $codigo_CAM, $correo);

                $statement->execute();
                $result = $statement->get_result();

                if ($result->numero_filas > 0) {
                    $usuario = $result->fetch_assoc();
                    
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_name'] = $usuario['Nombre'];

                    header("Location: http://localhost/HACKATHON/ProductoTiendaIvan/PHP/TiendaV2.php");
                    exit;
                } else {
                    $loginFallido = true;
                    print("¡El usuario o la contraseña están incorrectos!");
                }

                $statement->close();
            }
?>