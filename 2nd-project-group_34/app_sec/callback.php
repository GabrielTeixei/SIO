<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('126224864342-39u6sdhmd6taafepn90d42pm5kp8ubkt.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/app_sec/callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['id_token'] = $token['id_token'];

    // Debugging: Output the ID token
    var_dump($_SESSION['id_token']);

    // Redirect to the page where you verify the ID token
    header('Location: verifytoken.php');
    exit;
}