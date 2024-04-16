<?php
// Start the session
    session_start();
    $con = mysqli_connect('localhost', 'root');
    mysqli_select_db($con, 'sio');
    if($_SESSION['admin'] == 0){
        header("location: index.php");
    }
    $sql = "SELECT * FROM purchases";
    $result = mysqli_query($con, $sql);
    $purchases = mysqli_fetch_all($result, MYSQLI_ASSOC);


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
    <!-- Bootstrap Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Your PHP Content Goes Here -->
    <div class="container mt-4">
        <h1>Welcome to my Admin Page</h1>
        <p>This is a simple PHP page with a Bootstrap navbar.</p>
        <a href="add.php" class="btn btn-primary">Add Product</a>
        <a href="perfil.php" class="btn btn-primary">My perfil</a>
    </div>
    <div class="container mt-5">
        <h1 class="mb-4">All Purchases</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">UserID</th>
                    <th scope="col">Date</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Products</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchases as $purchase) { ?>
                    <tr data-bs-toggle="collapse" data-bs-target="#purchase-<?php echo $purchase['id']; ?>" aria-expanded="false" aria-controls="purchase-<?php echo $purchase['id']; ?>">
                        <td><?php echo htmlspecialchars($purchase['user_id'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($purchase['date'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($purchase['quantity'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($purchase['total'], ENT_QUOTES); ?></td>
                        <td><button class="btn btn-primary">Details</button></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="collapse" id="purchase-<?php echo $purchase['id']; ?>">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Unit Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sql = "SELECT * FROM purchase_product JOIN products ON id_prduct=products.id where id_purchase= '" . $purchase['id'] . "'";
                                        $result = mysqli_query($con, $sql);
                                        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                        foreach ($products as $product) { 
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($product['quantidade'], ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($product['price'], ENT_QUOTES); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
    </div>

    <div class="container mt-5">
    <h1 class="mb-4">All Users</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">User ID</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM user"; // Replace 'users' with the actual table name for your users
            $result = mysqli_query($con, $sql);
            $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
            foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($user['nome'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($user['admin'], ENT_QUOTES); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>




    

    <!-- Include Bootstrap JavaScript and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle."></script>
</body>

</html>