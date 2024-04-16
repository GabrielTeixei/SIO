<?php
require_once 'vendor/autoload.php';

session_start();
$client = new Google_Client();
$client->setClientId('126224864342-39u6sdhmd6taafepn90d42pm5kp8ubkt.apps.googleusercontent.com');


if (isset($_SESSION['id_token'])) {
    echo $_SESSION['id_token'];
    echo "aqui";
    $payload = $client->verifyIdToken($_SESSION['id_token']);
    
    if ($payload) {
        $_SESSION['user_id'] = $_SESSION['xuser_id'];
        $_SESSION['name'] = $_SESSION['xname'];
        $_SESSION['admin'] = $_SESSION['xadmin'];
        // ID token is valid, user is authenticated
        echo "Multi-Factor Authentication Successful!";
        unset($_SESSION['id_token']); // Clear the sensitive session data
        if($_SESSION['admin'] == 1){
            header('Location: admin.php');
        }else{
            header('Location: perfil.php');
        }
    } else {
        header('Location: logout.php');
    }
} else {
    header('Location: logout.php');
}
