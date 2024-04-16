<?php
session_start();
$con = mysqli_connect('localhost', 'root');
mysqli_select_db($con, 'sio');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

// Connect to the database
//$sql = "SELECT * FROM purchases JOIN products ON id_product=products.id where user_id = '" . $_SESSION['user_id'] . "'";
$sql = "SELECT * FROM purchases where user_id = '" . $_SESSION['user_id'] . "'";
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
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">Your Purchases</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Products</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchases as $purchase) { ?>
                    <tr data-bs-toggle="collapse" data-bs-target="#purchase-<?php echo $purchase['id']; ?>" aria-expanded="false" aria-controls="purchase-<?php echo $purchase['id']; ?>">
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
</body>
</html>
        

</body>

</html>