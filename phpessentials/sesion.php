<?php
  session_start([
    'cookie_lifetime' => 86400
  ]);
  
  require_once 'conexion.php';
  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();
    session_destroy();
  } else {
    $_SESSION['LAST_ACTIVITY'] = time();
  }
  
  if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
  } elseif (time() - $_SESSION['CREATED'] > 300) {

    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
  }
?>