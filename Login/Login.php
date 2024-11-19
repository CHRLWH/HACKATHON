   <?php
    // Parámetros de conexión a la BBDD
    $servername = "localhost";  // Servidor de la base de datos
    $username = "usuariophpdam";         // Usuario de la base de datos
    $password = "1234qwerty..";             // Contraseña de la base de datos
    $dbname = "hackaton"; // Nombre de la base de datos

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si el formulario ha sido enviado
    if (isset($_POST['submit'])) {
        $id = $_POST['id'];
        $codigoCAM = $_POST['codigoCAM'];

        // Consulta SQL para insertar los datos
        $sql = "INSERT INTO usuarios (id, codigoCAM) VALUES ('$id','$codigoCAM')";

        if ($conn->query($sql) === TRUE) {
            echo "Nuevo registro creado con éxito";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Cerrar la conexión
    $conn->close();
    ?>