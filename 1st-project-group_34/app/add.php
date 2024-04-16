<?php
// Start the session
    session_start();
    $con = mysqli_connect('localhost', 'root');
    mysqli_select_db($con, 'sio');
    if($_SESSION['admin'] == 0){
        header("location: index.php");
    }

    // Check if form submitted
    if(isset($_POST['add_product'])){
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_description = $_POST['product_description'];
        $product_category = $_POST['product_category'];
        $product_stock = $_POST['product_stock'];
        $product_img =  $_FILES['product_img']['name'];
        $img_path = '/detishop/images/'.$product_img;
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["product_img"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        ##$extensions_arr = array("jpg","jpeg","png","gif");
        ##if( in_array($imageFileType,$extensions_arr) ){
        $insert_product = "INSERT INTO products (name, price, descr, stock, img, category) VALUES ('$product_name', '$product_price', '$product_description','$product_stock', '$img_path', '$product_category')";
        mysqli_query($con, $insert_product);
        move_uploaded_file($_FILES['product_img']['tmp_name'],$target_dir.$product_img);
            //header("location: admin.php");
        
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <!-- Bootstrap Navbar -->
    <?php include 'navbar.php'; ?>
    <!-- Content -->
    <div class="container mt-4">
        <h1>Insert Product</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name:</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label">Product Price:</label>
                <input type="number" class="form-control" id="product_price" name="product_price" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="product_description" class="form-label">Product Description:</label>
                <textarea class="form-control" id="product_description" name="product_description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="product_category" class="form-label">Product Category:</label>
                <input type="text" class="form-control" id="product_category" name="product_category" required>
            </div>
            <div class="mb-3">
                <label for="product_stock" class="form-label">Product Stock:</label>
                <input type="number" class="form-control" id="product_stock" name="product_stock" required>
            </div>
            <div class="mb-3 d-flex">
                <div class="me-3">
                    <label for="product_img" class="form-label">Product Image:</label>
                    <input type="file" class="form-control" id="product_img" name="product_img" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
            </div>

          
        </form>
    
    </div>
    <?php include 'footer.php'; ?>

    <!-- Include Bootstrap JavaScript and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
