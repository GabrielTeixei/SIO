<?php
    session_start();
    session_unset();
    session_destroy();
    setcookie("PHPSESSID", session_id(), [
        'expires' => time() - 3600,
        'path' => '/app_sec/',
        'secure' => true, 
        'httponly' => true, 
        'samesite' => 'Lax' 
    ]);
    header("Location: index.php");
    exit();
?>
