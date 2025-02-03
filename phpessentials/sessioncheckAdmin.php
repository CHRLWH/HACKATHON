<?php
session_start();
if (!isset($_SESSION['admin_id'])) {

    header("Location: http://localhost/HACKATHON/Login/Login.php");
    exit;
}
?>
