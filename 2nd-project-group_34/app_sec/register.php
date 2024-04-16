<?php




function isPasswordCompromised($password) {
    $hashedPassword = strtoupper(sha1($password)); // HIBP usa SHA-1 hash
    $hashPrefix = substr($hashedPassword, 0, 5);
    $hashSuffix = substr($hashedPassword, 5);

    $apiUrl = "https://api.pwnedpasswords.com/range/" . $hashPrefix;

    $response = file_get_contents($apiUrl);

    $hashList = explode("\n", $response);
    
    foreach ($hashList as $apihashes) {
        $apihash = explode(":", $apihashes);
        $apihashsuffix = $apihash[0];

        if ($apihashsuffix === $hashSuffix) {
            return true; // Senha comprometida se o contador for maior que zero
        }
    }

    return false; // Senha não comprometida
}



function validate_password($password) {
    $errors = array();
    if (strlen($password) < 12) {
        $errors[] = 'Password must be at least 12 characters long';
    }
    if (strlen($password) > 128) {
        $errors[] = 'Password must be at most 128 characters long';
    }
    if (isPasswordCompromised($password)) {
        $errors[] = 'Password is compromised';
    }

    return $errors;
}

function isUsernameAvailable($username) {
    $conn = mysqli_connect('localhost', 'root');
    mysqli_select_db($conn, 'sio');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username already exists in the database
    $stmt = $conn->prepare("SELECT * FROM user WHERE nome = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return false; // Username is not available
    }

    return true; // Username is available
}







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
    } else{
        $errors = array_merge($errors, validate_password($password));
        
    }
    if ($password != $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    if (!isUsernameAvailable($username)) {
        $errors[] = 'Username is not available';
    }

    // Check if the username is available
    if (empty($errors)) {
        $conn = mysqli_connect('localhost', 'root');
        mysqli_select_db($conn, 'sio');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the username already exists in the database
        $stmt = $conn->prepare("SELECT * FROM user WHERE nome = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = 'Username is not available';
        }

        $stmt->close();
    }

    // If there are no errors, save the user to the database
    if (empty($errors)) {
        // ... existing code to save the user ...
    }

    if ($password != $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    // If there are no errors, save the user to the database
    if (empty($errors)) {

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">User Registration</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mt-4">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group mt-4">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password">
                <div class="progress mt-2">
                    <div id="password-strength" class="progress-bar-striped" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" name="confirm_password" id="confirm_password">
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Register</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle."></script>
    
    <script>
            document.getElementById('password').addEventListener('input', function () {
                checkPasswordStrength();
            });
        function checkPasswordStrength() {
            var password = document.getElementById('password').value;
            var progressBar = document.getElementById('password-strength');
            var strength = password.length * 100/12;

            /*if (password.match(/[a-z]+/)) {
                strength += 1;
            }
            if (password.match(/[A-Z]+/)) {
                strength += 1;
            }
            if (password.match(/[0-9]+/)) {
                strength += 1;
            }
            if (password.match(/[$@#&!]+/)) {
                strength += 1;
            }*/

            // Atualizar a largura da barra de progresso com base na força
            //progressBar.style.width = (strength * 25) + '%';
            progressBar.style.width = strength + '%';

            // Adicionar classes para estilizar a barra com diferentes cores
            progressBar.className = 'progress-bar-striped progress-bar-animated';

            if (strength < 33) {
                progressBar.classList.add('bg-danger');
            }
            else if (strength < 66) {
                progressBar.classList.add('bg-warning');
            }
            else if (strength < 100){
                progressBar.classList.add('bg-info');
            }
            else {
                progressBar.className ='bg-success';
            }


        }
    </script>

</body>
</html>
