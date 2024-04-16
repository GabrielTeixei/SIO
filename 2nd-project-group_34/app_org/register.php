<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the form data
    $errors = array();
    if (empty($username)) {
        $errors[] = 'Username is required';
    }
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    if ($password != $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    // If there are no errors, save the user to the database
    if (empty($errors)) {
        // Encrypt the password
        #$hashed_password = password_hash($password, PASSWORD_DEFAULT);
        //print $hashed_password;
        #echo "pass" . $hashed_password . "";
        // TODO: Save the user to the database
        echo 'User registered successfully';

        // Establish a database connection
        $conn = mysqli_connect('localhost', 'root');
        mysqli_select_db($conn, 'sio');


        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO user (nome, email, pass) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Set the parameters and execute the statement
        $username = $_POST['username'];
        $email = $_POST['email'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt->execute();

        // Close the statement and the connection
        $stmt->close();
        $conn->close();

        header('Location: index.php');
    }
}

?><!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

        .container {
            background-color: #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }

        form {
            max-width: 300px;
            margin: 0 auto;
        }

        div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button.center {
            display: block;
            margin: 0 auto;
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button.center:hover {
            background-color: #45a049;
        }

        ul {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Registration</h1>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password">
            </div>
            <div>
                <button class="center" type="submit">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
