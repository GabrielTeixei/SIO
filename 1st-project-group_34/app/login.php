<?php

session_start();

$con = mysqli_connect('localhost', 'root');
mysqli_select_db($con, 'sio');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Use prepared statements to prevent SQL injection

    //admin' and 1=1 -- //

    $username = $_POST['username'];
    $password = $_POST['password'];
    //unsafe
    $sql = "SELECT * FROM user WHERE nome = '$username' AND pass = '$password'";
    $result = mysqli_query($con, $sql);
    

    $row = mysqli_fetch_array($result);
    if(mysqli_num_rows($result) == 1){
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
        /* Style the Register button */
.register-button {
    background-color: gray;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 18px;
    margin-left: 100px; /* Add margin to move the button to the right */
}

/* Hover effect for the Register button */
.register-button:hover {
    background-color: darkred;
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
      <!-- Apply custom CSS class for styling -->
             <input type="button" class="register-button" onclick="location.href='register.php';" value="Register" />
    </form>
    </div>
</body>
</html>
