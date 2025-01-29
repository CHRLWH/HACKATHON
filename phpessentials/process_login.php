<?php
session_start();
require_once "db_connect.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    $sql = "SELECT id, username, password FROM users WHERE username = :username";
    
    if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                if($row = $stmt->fetch()){
                    $id = $row["id"];
                    $username = $row["username"];
                    $hashed_password = $row["password"];
                    if(password_verify($password, $hashed_password)){
                        session_start();
                        
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;                            
                        
                        header("location: welcome.php");
                    } else{
                        $login_err = "Usuario o contraseña incorrectos.";
                    }
                }
            } else{
                $login_err = "Usuario o contraseña incorrectos.";
            }
        } else{
            echo "Oops! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }

        unset($stmt);
    }
}
unset($pdo);
?>