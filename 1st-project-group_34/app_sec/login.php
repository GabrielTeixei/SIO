<?php
session_start();

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
    if(mysqli_num_rows($result) == 1 && password_verify($password, $row['pass'])){
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $row['nome'];
        $_SESSION['admin'] = $row['admin'];

        $user_id = $_SESSION['user_id'];

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
            header("location: admin.php");
        }else{
            echo "user";
            header("location: index.php");
        }
        //get cart id


    }else{
        echo "Login failed";
    }
}




?>


<html>
<head>
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

            <input type="submit" value="Login">

            <input type="button" onclick="location.href='register.php';" value="Register">
        </form>
    </div>
</body>
</html>