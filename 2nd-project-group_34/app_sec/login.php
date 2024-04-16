<?php

session_set_cookie_params(['path' => '/app_sec/']);
require_once 'vendor/autoload.php';
session_unset();
session_start();
session_regenerate_id(true);
$sessionId = session_id();
setcookie("PHPSESSID", $sessionId, [
    'expires' => time() + 3600,
    'path' => '/app_sec/',
    'secure' => true, 
    'httponly' => true, 
    'samesite' => 'Lax' 
]);



$client = new Google_Client();
$client->setClientId('126224864342-39u6sdhmd6taafepn90d42pm5kp8ubkt.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/app_sec/callback.php');
$client->addScope('email');
$client->addScope('profile');


$authUrl = $client->createAuthUrl();

$con = mysqli_connect('localhost', 'root');
mysqli_select_db($con, 'sio');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    


    $username = $_POST['username'];
    $password = $_POST['password'];



    $stmt = mysqli_prepare($con, "SELECT * FROM user WHERE nome = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_array($result);

    $recaptchaSecretKey = "6LddTSMpAAAAAHHlsofeGE-QSX8siNvXDLFU_RVk"; // Replace with your secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecretKey}&response={$recaptchaResponse}");
    $recaptchaData = json_decode($recaptchaVerify);

    if ($recaptchaData->success) {
        if(mysqli_num_rows($result) == 1 && password_verify($password, $row['pass'])){
            $_SESSION['xuser_id'] = $row['id'];
            $_SESSION['xname'] = $row['nome'];
            $_SESSION['xadmin'] = $row['admin'];
            $user_id = $_SESSION['xuser_id'];
    
            //create cart if not exists
            $userCart = mysqli_query($con, "SELECT COUNT(*) as total FROM cart_user WHERE user_id = '$user_id'");
            $data=mysqli_fetch_assoc($userCart);
            if ($data['total']==0){
                $insert_cart = "INSERT INTO cart_user (user_id,total) VALUES ('$user_id',0)";
                mysqli_query($con, $insert_cart);
                
            }
    
            //get cart id
            
            $userCart = mysqli_query($con, "SELECT * FROM cart_user WHERE user_id = '$user_id'");
            $userCart = mysqli_fetch_array($userCart);  
            $_SESSION['cart_id'] = $userCart['id'];
            //print
            echo $_SESSION['cart_id'];        
    
            if($_SESSION['admin'] == 1){
                echo "admin";
                //header("location: admin.php");
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                exit;
            }else{
                echo "user";
                //header("location: index.php");
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                exit;
            }
            //get cart id
    
    
        }else{
            echo "Login failed";
        }

    } else {
        echo "You are a robot";
    }
    
}


//admin
//bmtryioyh904etis0¨&
//bmtryioyh904etis0¨&zzz
?>


<html>
<head>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>User And Admin Login System In PHP MySQL Step By Step - tutsmake.com</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);
        }

        .login-container h2 {
            text-align: center;
        }

        .login-form input[type="text"], .login-form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 20px 0;
            box-sizing: border-box;
            font-size: 16px;
        }

        .login-form input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

        .login-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .login-form p {
            color: red;
            text-align: center;
        }

        .login-form input[type="button"] {
            background-color: #008CBA;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            
            
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        <form class="login-form" method="post" action="">
            <label>Username:</label>
            <input type="text" name="username" required><br>

            <label>Password:</label>
            <input type="password" name="password" required><br>

            <div class="g-recaptcha" data-sitekey="6LddTSMpAAAAAOgvG6IHOh64sYltKM2WtdiIQsbA"></div>

            <input type="submit" value="Login">

            <input type="button" onclick="location.href='register.php';" value="Register">
        </form>
    </div>
</body>
</html>