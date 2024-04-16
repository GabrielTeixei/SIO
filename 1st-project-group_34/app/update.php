<?php
session_start();
$con = mysqli_connect('localhost', 'root');
mysqli_select_db($con, 'sio');
//if($_SESSION['admin'] == 0){
//    header("location: index.php");
//}

if(isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($con, $query);
    $product = mysqli_fetch_assoc($result);

    if(isset($_POST['update_product'])) {
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_description = $_POST['product_description'];
        $product_category = $_POST['product_category'];
        $product_stock = $_POST['product_stock'];

        if(isset($_FILES['product_img']) && !empty($_FILES['product_img']['name'])) {
            $product_img = $_FILES['product_img']['name'];
            $img_path = '/detishop/images/' . $product_img;
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES["product_img"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $extensions_arr = array("jpg", "jpeg", "png", "gif");

            if(in_array($imageFileType, $extensions_arr)) {
                move_uploaded_file($_FILES['product_img']['tmp_name'], $target_dir . $product_img);
            }
        } else {
            $img_path = $product['img']; // Keep the existing image if not updated
        }

        $update_product = "UPDATE products SET name = '$product_name', price = '$product_price', descr = '$product_description', stock = '$product_stock', img = '$img_path', category = '$product_category' WHERE id = $product_id";
        mysqli_query($con, $update_product);
        header("location: admin.php");
    }
}
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
      

        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-4">
        <h1 class="text-center">Update Product</h1>
    </div>

    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" class="form-control" name="product_name" id="product_name" required value="<?php echo $product['name']; ?>">
            </div>

            <div class="form-group">
                <label for="product_price">Product Price:</label>
                <input type="number" class="form-control" name="product_price" id="product_price" step="0.01" required value="<?php echo $product['price']; ?>">
            </div>

            <div class="form-group">
                <label for="product_description">Product Description:</label>
                <textarea class="form-control" name="product_description" id="product_description" rows="4" required><?php echo $product['descr']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="product_category">Product Category:</label>
                <input type="text" class="form-control" name="product_category" id="product_category" required value="<?php echo $product['category']; ?>">
            </div>

            <div class="form-group">
                <label for="product_stock">Product Stock:</label>
                <input type="number" class="form-control" name="product_stock" id="product_stock" required value="<?php echo $product['stock']; ?>">
            </div>

            <div class="form-group">
                <label for="product_img">Product Image:</label>
                <input type="file" class="form-control-file" name="product_img" id="product_img">
            </div>

            <button type="submit" class="btn btn-primary" name="update_product">Update Product</button>
        </form>
    </div>

    <!-- Include necessary Bootstrap JavaScript and jQuery (similar to the previous page) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>

    <?php include('footer.php'); ?>
</body>

</html>
