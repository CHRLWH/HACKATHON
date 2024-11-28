<?php
    $servername = "x";
    $username = "x";
    $password = "x";
    $database = "x";

    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $nombre=$_POST['nombre'];
        $txt=$_POST['txt'];
        $fecha=$_POST['date'];
        $file=$_FILES['imagen'];
        $nombreem=$_POST['nombreem'];
        move_uploaded_file($file["tmp_name"], "img/" . $file["name"]);

        $sql = "SELECT id FROM empresa WHERE nombre = '".$nombreem."'";
        $result = $conn->query($sql);
        $idem = $result->fetch_array()[0];

        $comprobarnom =check("SELECT id FROM encuesta WHERE nombre = '".$nombre."'","Nombre de encuesta existente",$conn);
        
        if($comprobarnom){
            $sql = "INSERT INTO encuesta (nombre, texto, imagen,fechalimite,idempresa)
            VALUES ('$nombre', '$txt', '".$file['name']."','$fecha','$idem')";
            if ($conn->query($sql) === TRUE) {
                header("Location: PageEmpresas.php");
                $conn->close();
            }else{
                echo "Error al insertar datos: " . $conn->error;
                $conn->close();
            }
        }
        
    } function check($sql,$msg,$conn) {
        $result = $conn->query($sql);
        $res;
        if(!$result){
            echo "Error en la consulta: " . $conn->error;
            $res = false;
        }
        if($result->num_rows == 0){
            $res= true;

        }else{
            message($msg, $conn);
            $res= false;
        }
        return $res;
    }
    function message($msg, $conn) {
        echo $msg;
        $conn->close();
    }
?>