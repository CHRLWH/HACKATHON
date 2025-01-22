
<?php
    require_once '../../phpessentials/sesion.php';

    $codigo_CAM = trim($_POST['codigo_CAM']);
            $correo = trim($_POST['correo']);

            if (empty($codigo_CAM) || empty($correo)) {
                $loginFailed = true;
            } else {
            
                $stmt = $conn->prepare("SELECT * FROM usuario WHERE codigo_CAM = ? AND correo = ?");
                $stmt->bind_param("is", $codigo_CAM, $correo);

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['Nombre'];

                    header("Location: http://localhost/HACKATHON/ProductoTiendaIvan/PHP/TiendaV2.php");
                    exit;
                } else {
                    $loginFailed = true;
                    print("¡El usuario o la contraseña están incorrectos!");
                }

                $stmt->close();
            }
?>