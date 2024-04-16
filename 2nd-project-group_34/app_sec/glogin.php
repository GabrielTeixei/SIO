<?php
    require __DIR__ . '/vendor/autoload.php';

    session_start();

    $client = new Google\Client();
    $client->setClientId('126224864342-39u6sdhmd6taafepn90d42pm5kp8ubkt.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://localhost/app_sec/');
    $client->addScope('email');
    $client->addScope('profile');

    if (isset($_GET['code'])) {
        $client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();
        header('Location: ' . filter_var('http://localhost/', FILTER_SANITIZE_URL));
    }

    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google\Service\Oauth2($client);
        $userInfo = $service->userinfo->get(); // Get user information
        // Process user information as needed
    } else {
        $authUrl = $client->createAuthUrl();

        echo "<a href='$authUrl'>Login with Google</a>";
    }
?>