<?php
session_start();
$con = mysqli_connect('localhost', 'root');
mysqli_select_db($con, 'sio');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate the form data
    $errors = array();
    if ($newPassword === $currentPassword) {
        $errors[] = 'New password must be different from the current password';
    }
    if (strlen($newPassword) < 12) {
        $errors[] = 'Password must be at least 12 characters long';
    }
    if (strlen($newPassword) > 128) {
        $errors[] = 'Password must be at most 128 characters long';
    }
    if (isPasswordCompromised($newPassword)) {
        $errors[] = 'Password is compromised';
    }
    if ($newPassword !== $confirmPassword) {
        $errors[] = 'Passwords do not match';
    }

    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];

        $stmt = mysqli_prepare($con, "SELECT * FROM user WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "s", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        $row = mysqli_fetch_array($result);

        // Check if the current password is correct

        if (password_verify($currentPassword, $row['pass'])) {
            // Update the user's password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $con->prepare("UPDATE user SET pass = ? WHERE id = ?");
            $stmt->bind_param("si", $newPasswordHash, $user_id);
            $stmt->execute();
            $stmt->close();
            $con->close();
            // Redirect to the profile page
            header('Location: perfil.php');
            exit();
        } else {
            $errors[] = 'Current password is incorrect';
        }
    }
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Page with Bootstrap Navbar</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">

        <form method="post">
        <div class="form-group">
                <label for="current_password">Confirm Password:</label>
                <input type="password" class="form-control" name="current_password" id="current_password">
            </div>
            <div class="form-group">
                <label for="new_password">Password:</label>
                <input type="password" class="form-control" name="new_password" id="new_password">
                <div class="progress mt-2">
                    <div id="password-strength" class="progress-bar-striped" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" name="confirm_password" id="confirm_password">
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle."></script>
    
    <script>
            document.getElementById('new_password').addEventListener('input', function () {
                checkPasswordStrength();
            });
        function checkPasswordStrength() {
            var password = document.getElementById('new_password').value;
            var progressBar = document.getElementById('password-strength');
            var strength = password.length * 100/12;

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
                progressBar.classList.add('bg-success');
            }
        }
    </script>

</body>
</html>
        
