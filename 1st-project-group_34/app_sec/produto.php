<?php
// Start the session
session_start();
$con = mysqli_connect('localhost', 'root');
mysqli_select_db($con, 'sio');

// Check if a product ID is provided via GET parameter
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Construct SQL query to fetch the selected product
    $sql = "SELECT * FROM `products` WHERE id = '$product_id'";

    $result = mysqli_query($con, $sql);

    // Check if a product with the provided ID exists
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        // Product not found
        $product = false;
    }
} else {
    // No product ID provided, display a message or redirect to a product selection page
    $product = false;
}
?>

<?php
$con = mysqli_connect('localhost', 'root');
mysqli_select_db($con, 'sio');
$sql = "SELECT * FROM products";

if (isset($_POST['add'])) {
    $p_id = $_POST['id'];
    $user_id = $_SESSION['user_id'];
    $quantidade = 1;
    $userCart = mysqli_query($con, "SELECT COUNT(*) as total FROM cart_user WHERE user_id = '$user_id'");
    $data = mysqli_fetch_assoc($userCart);
    if ($data['total'] == 0) {
        $insert_cart = "INSERT INTO cart_user (user_id) VALUES ('$user_id')";
        mysqli_query($con, $insert_cart);
    }
    $userCart = mysqli_query($con, "SELECT * FROM cart_user WHERE user_id = '$user_id'");
    if ($userCart) {
        $row = mysqli_fetch_array($userCart);
        $cart_id = $row['id'];
        $_SESSION['cart_id'] = $cart_id;
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
        $_SESSION['atual_cart'] = $data['total'];
        header("Refresh:0");
        //header("Location: index.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupere os dados do formulário
    $product_id = $_POST['product_id'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];
    $user_id = $_SESSION['user_id'];

    // Insira a revisão no banco de dados
    $sql = "INSERT INTO `reviews` (id_produto, comment, rating, id_user) VALUES ('$product_id', '$comment', '$rating', '$user_id')";
    //mysqli_query($con, $sql);

    mysqli_query($con, $sql);

    header("Refresh:0");
    //echo "Review added successfully!";

}


?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Details</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Estilos para o formulário de revisão */
        .review-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .review-form label,
        .review-form input,
        .review-form textarea {
            margin-bottom: 10px;
            display: block;
            width: 100%;
        }

        .review-form input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }

        .review-form input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php include('navbar.php'); ?>

    <!-- Your PHP Content Goes Here -->
    <div class="container mt-4">
        <?php
        if ($product) {
            // Display product details
        ?>

            <div class="row">
                <div class="col-6">

                    <!-- Display the product image -->
                    <img src="<?= $product['img']; ?>" class="img-fluid " height="500px" width="500px" alt="<?= $product['name']; ?>">

                </div>


                <!-- Add any other product details you want to display -->
                <div class="col-6">
                    <h1>Product Details</h1>
                    </br>

                    <p>Produto: <?= $product['name'] ?></p>
                    <p>Preço: <?= $product['price'] ?> €</p>
                    <p>Descrição: <?= $product['descr'] ?></p>
                    <p>Categoria: <?= $product['category'] ?></p>
                    </br>
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-warning align" name="add"><i class="fa-solid fa-cart-shopping"></i> Adicionar carrinho</button>
                        <?php
                        if (isset($_SESSION['user_id']) and $_SESSION['admin'] == 1) {
                        ?>
                            <a href="update.php?id=<?= $product['id'] ?>" class="btn btn-success"><i class="fas fa-edit"></i> Edit</a>
                        <?php
                        }
                        ?>
                    </form>

                </div>
            </div>
        <?php

        } else {
            // Product not found or no product ID provided
            echo "<h1>Product Not Found</h1>";
        }
        ?>
    </div>
    <!--aqui vai a reviews -->
    <div class="container mt-4">
        <h1>Reviews</h1>
        <?php
        // Verifica se o 'id' está presente na URL
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            // Constrói a consulta SQL para buscar as revisões
            $sql = "SELECT * FROM `reviews` JOIN user on id_user=user.id WHERE id_produto = '$product_id'";
            $result = mysqli_query($con, $sql);
            $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Verifica se há revisões para exibir
            if (count($reviews) > 0) {
                // Exibe as revisões
                foreach ($reviews as $review) {
        ?>
                    <div class="card my-2 p-3">

                        <p class="mb-0"><strong>Review:</strong> <?= htmlentities($review['comment']) ?></p>
                        <p class="mb-0"><strong>Rating:</strong> <?= htmlentities($review['rating']) ?></p>
                        <p class="mb-0"><strong>Author:</strong> <?= htmlentities($review['nome']) ?></p>

                    </div>
        <?php
                }
            } else {
                // Nenhuma revisão encontrada 
                echo "<p>No reviews available</p>";
            }
        } else {
            // 'id' não fornecido na URL
            echo "<p>No product ID provided</p>";
        }
        ?>
        <!-- Parte HTML do formulário de revisão -->
        <?php if (isset($_SESSION['user_id'])) : ?>
            <div class="container">
                </br>
                <h2 class="text-center mb-4">Adicionar uma Avaliação</h2>
                <form method="post" action="" class="row justify-content-center">
                    <input type="hidden" name="product_id" value="<?= $product_id ?>" class="form-control">
                    <div class="col-md-6 mb-3">
                        <label for="comment">Comentário:</label>
                        <textarea name="comment" id="comment" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="rating">Avaliação (1-5):</label>
                        <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary" style="margin-top: 32px;">Enviar Avaliação</button>
                    </div>
                </form>
            </div>

        <?php else : ?>
            <p>Login to add a review.</p>
        <?php endif; ?>


        <?php include('footer.php'); ?>
    </div>





</body>

</html>