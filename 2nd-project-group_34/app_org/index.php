<?php
// Start the session
session_start();
$con = mysqli_connect('localhost', 'root');
mysqli_select_db($con, 'sio');
//$sql = "SELECT * FROM products";
$stmt = mysqli_prepare($con, "SELECT * FROM products");

if (isset($_GET['category'])) {
    $cat = $_GET['category'];
    //$sql = "SELECT * FROM products WHERE category = '$cat'";
    $stmt = mysqli_prepare($con, "SELECT * FROM products WHERE category = ?");
    mysqli_stmt_bind_param($stmt, "s", $cat);
    //mysqli_stmt_execute($stmt);
}


if (isset($_POST['add'])) {
    if (isset($_SESSION['cart_id'])) {
        $p_id = $_POST['id'];
        $user_id = $_SESSION['user_id'];
        $quantidade = 1;

        $userCart = mysqli_query($con, "SELECT * FROM cart_user WHERE user_id = '$user_id'");

        $row = mysqli_fetch_array($userCart);
        $cart_id = $_SESSION['cart_id'];

        //check if produxt already in cart
        $check_product = mysqli_query($con, "SELECT * FROM carts WHERE product_id = '$p_id' AND cart_id = '$cart_id'");
        if (mysqli_num_rows($check_product) > 0) {
            $row = mysqli_fetch_array($check_product);
            $quantidade = $row['quantidade'] + 1;
            $update_product = "UPDATE carts SET quantidade = '$quantidade' WHERE product_id = '$p_id' AND cart_id = '$cart_id'";
            mysqli_query($con, $update_product);
        } else {
            $insert_product = "INSERT INTO carts (product_id, quantidade, cart_id) VALUES ('$p_id', '$quantidade', '$cart_id')";
            mysqli_query($con, $insert_product);
        }

        #$_SESSION['atual_cart'] = $data['total'];
        header("Location: index.php");
    }
}

//list all categorys

$sql_cat = "SELECT DISTINCT category FROM `products`";
$result = mysqli_query($con, $sql_cat);
$categorys = mysqli_fetch_all($result, MYSQLI_ASSOC);

//ca' UNION SELECT id,nome,pass,null,null,null,null from user; -- //
//pesquisa por nome
if (isset($_GET['search'])) {
    $pat = $_GET['search'];
    $pat = "%{$_GET['search']}%";
    //$sql = "SELECT * FROM products WHERE name LIKE '%" . $_GET['search'] . "%'";
    $stmt = mysqli_prepare($con, "SELECT * FROM products WHERE name LIKE ?");
    mysqli_stmt_bind_param($stmt, "s", $pat);
    //mysqli_stmt_execute($stmt);
}
//

//pesquisa por preço
if (isset($_GET['min']) && isset($_GET['max'])) {
    $min = $_GET['min'];
    $max = $_GET['max'];
    //$sql = "SELECT * FROM products WHERE price BETWEEN '$min' AND '$max'";
    $stmt = mysqli_prepare($con, "SELECT * FROM products WHERE price BETWEEN ? AND ?");
    mysqli_stmt_bind_param($stmt, "ss", $min, $max);
    //mysqli_stmt_execute($stmt);
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
  

    <!-- Bootstrap Navbar -->
    <?php include('navbar.php'); ?>

 
<!-- Rest of your existing content -->

    <div class="container mt-4">
        
        <h1>Welcome to my Detishop</h1>
        <p>Here you can find the products for the best prices</p>
        
        <!--dropdown-->
        <div class="d-flex align-items-center">
            <div class="dropdown me-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Categorias
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <?php foreach($categorys as $category): ?>
                        <!--ao clicar quero que me mostre os produtos dessa categoria-->
                        <li><a class="dropdown-item" href="index.php?category=<?= $category['category'] ?>"><?= $category['category'] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <form class="d-flex me-2" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <a href="index.php" class="btn btn-danger me-2">Reset</a>
   
            <form class="d-flex justify-content-center my-3 me-2" role="search">
            <div class="form-group me-2">
                <label for="min" class="visually-hidden">Valor mínimo</label>
                <input class="form-control" type="number" step="0.01" placeholder="Min" aria-label="Min" name="min" id="min">
            </div>
            <div class="form-group me-2">
                <label for="max" class="visually-hidden">Valor máximo</label>
                <input class="form-control" type="number" step="0.01" placeholder="Max" aria-label="Max" name="max" id="max">
            </div>
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
            
        </div>

       



    </div>
 
    <div class="container">
        <div class="row">
            <?php
            #$result = mysqli_query($con, $sql);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            
            #echo mysqli_num_rows($result);
            while ($row = mysqli_fetch_array($result)):
            ?>
                <div class="col-md-4 text-center">
                    <br>
                    <div class="card">
                        <form method="post" action="index.php">
                            <img src="<?php echo $row['img']; ?>" height="250px" width="auto">

                            <div class="card-body">
                        <h5 class="card-title"><?= $row['name'] ?></h5>
                        <?php if ($row['stock'] != 0){ ?>
                            <p class="card-text"><span class="badge bg-success">Stock: <?= $row['stock'] ?></p>
                        <?php }else{ ?>
                            <p class="card-text"><span class="badge bg-danger">Out of stock</span></p>
                        <?php } ?>
                        <p class="card-text"><?= $row['price'] ?> €</p>
                        <a href="produto.php?id=<?= $row['id'] ?>" class="btn btn-primary align">Ver Mais</a>

                        <!-- button to add do cart -->
                        <button type="submit" class="btn btn-warning align" name="add" ><i class="fa-solid fa-cart-shopping"></i>  Adicionar carrinho</button>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
  
                    </div>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
            
          
        </div>
          
    </div>

    <?php include('footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle."></script>
</body>

</html>