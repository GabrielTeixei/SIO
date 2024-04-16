<?php
// Start the session
session_start();
$con = mysqli_connect('localhost', 'root');
mysqli_select_db($con, 'sio');
$card_id = $_SESSION['cart_id'];
$cartquatity = mysqli_query($con, "SELECT SUM(quantidade) as total FROM carts WHERE cart_id = '$card_id'");
$data = mysqli_fetch_assoc($cartquatity);
$count = $data['total'];
$sql = "SELECT * FROM `carts` JOIN products ON product_id=products.id where cart_id = '$card_id'";

if (isset($_POST['reset'])) {
    $card_id = $_SESSION['cart_id'];
    $sqlr = "DELETE FROM carts WHERE cart_id = '$card_id'";
    mysqli_query($con, $sqlr);
    //unset($_SESSION['atual_cart']);
    header("Location: cart.php");
}

//SELECT SUM(quantidade*price) from carts JOIN cart_user ON cart_id=cart_user.id JOIN products ON carts.product_id=products.id WHERE cart_user.id=13;


if (isset($_POST['finalizar'])) {
    $card_id = $_SESSION['cart_id'];
    $user_id = $_SESSION['user_id'];
    //reduzir a quantidade de produtos na base de dados
    $sqlf = "SELECT * FROM `carts` JOIN products ON product_id=products.id where cart_id = '$card_id'";
    $result = mysqli_query($con, $sqlf);
    while ($row = mysqli_fetch_assoc($result)) {
        $quantidade = $row['quantidade'];
        $product_id = $row['product_id'];
        $sqlf = "UPDATE products SET stock = stock - '$quantidade' WHERE id = '$product_id'";
        mysqli_query($con, $sqlf);
    }

    //inserir na tabela purchases
    $sqltotal = "SELECT * FROM `cart_user` where id ='$card_id'";
    //print card_id;
    echo $card_id;
    $result = mysqli_query($con, $sqltotal);
    $data = mysqli_fetch_assoc($result);

    $sqlc = "INSERT INTO purchases(user_id,date,total,quantity) VALUES ('$user_id',NOW(),'$data[total]', '$count')";
    mysqli_query($con, $sqlc);

    //get purchase id
    $sqlp = "SELECT id FROM purchases WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($con, $sqlp);
    $data = mysqli_fetch_assoc($result);
    $purchase_id = $data['id'];
    //echo $purchase_id;
    //inserir na tabela purchases_products
    $sqlf = "SELECT * FROM `carts` JOIN products ON product_id=products.id where cart_id = '$card_id'";
    $result = mysqli_query($con, $sqlf);
    while ($row = mysqli_fetch_assoc($result)) {
        $quantidade = $row['quantidade'];
        $product_id = $row['product_id'];
        $sqlf = "INSERT INTO purchase_product(id_prduct,id_purchase,quantidade) VALUES ('$product_id','$purchase_id','$quantidade')";
        mysqli_query($con, $sqlf);
    }
    //get quantity of products
    //$sqlf = "INSERT INTO purchases (product_id, quantidade, user_id) SELECT product_id, quantidade,'$user_id'  FROM carts WHERE cart_id = '$card_id'";
    //mysqli_query($con, $sqlf);
    $sqlf = "DELETE FROM carts WHERE cart_id = '$card_id'";
    mysqli_query($con, $sqlf);
    //unset($_SESSION['atual_cart']);
    header("Location: index.php");
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

    <!-- Your PHP Content Goes Here -->
    <div class="container mt-4">
    </div>
    <div class="container">
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Produto</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    if (isset($_SESSION['cart_id']) and $count > 0) {

                        $result = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {

                    ?>
                            <tr>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['price'] ?> €</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="updateQuantity( <?= $row['price'] ?>,<?= $row['product_id'] ?>, 'increase')">+</button>
                                    <span id="quantity_<?= $row['product_id'] ?>"><?= $row['quantidade'] ?></span>
                                    <button class="btn btn-sm btn-outline-primary" onclick="updateQuantity(<?= $row['price'] ?>,<?= $row['product_id'] ?>, 'decrease')">-</button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeProduct(<?= $row['product_id'] ?>)">Remove</button>
                                </td>


                            </tr>
                    <?php
                            $total = $total + $row['price'] * $row['quantidade'];
                        }

                        echo '<h5>Total: <span id="total-amount">' . $total . '</span> €</h5>';
                    } else {
                        echo "<h5>Cart is Empty</h5>";
                    }
                    ?>
                </tbody>
            </table>
            <form method="post" action="">
                <input type="submit" name="reset" value="Reset Cart" class="btn btn-danger">
            </form>
            <form method="post" action="">
                <input type="submit" name="finalizar" value="Finalizar Compra" class="btn btn-primary">
            </form>





        </div>
    </div>



    <!-- Include Bootstrap JavaScript and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle."></script>

    <script>
        function updateQuantity(price, productId, action) {
            console.log(<?php echo $total; ?>);
            const quantityElement = document.getElementById(`quantity_${productId}`);
            const currentQuantity = parseInt(quantityElement.innerText);
            var totalElement = document.getElementById('total-amount');
            var total = parseFloat(totalElement.innerText);

            var newQuantity;
            if (action === 'increase') {
                // Aumentar a quantidade
                newQuantity = currentQuantity + 1;
                total += price;
                document.getElementById('cart-count').innerText = parseInt(document.getElementById('cart-count').innerText) + 1;
                } else if (action === 'decrease' && currentQuantity > 1) {
            
                // Diminuir a quantidade (não permitir quantidade menor que 1)
                newQuantity = currentQuantity - 1;
                total -= price;
                document.getElementById('cart-count').innerText = parseInt(document.getElementById('cart-count').innerText) - 1;
            } else {
                return; // Não faz nada se a ação for inválida
            }
            totalElement.innerText = total.toFixed(2);
            // Enviar a solicitação AJAX para atualizar a quantidade no servidor
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('new_quantity', newQuantity);

            fetch('update_quantity.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualizar a exibição da quantidade na página
                        quantityElement.innerText = newQuantity;
                    } else {
                        // Lida com erros, se houver
                        console.error('Erro ao atualizar a quantidade.');
                    }
                });
            fetch('cart_total.php')
                .then(response => {
                    if (response.ok) {
                        console.log('PHP script executed successfully.');
                    } else {
                        console.error('Error:', response.status, response.statusText);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }

        function removeProduct(productId) {
            const confirmation = confirm('Are you sure you want to remove this product from the cart?');
            if (!confirmation) {
                return; // Do nothing if the user cancels the confirmation.
            }

            const formData = new FormData();
            formData.append('product_id', productId);

            fetch('remove_product.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Product removed successfully, update the UI
                        location.reload(); // Refresh the page to reflect the updated cart
                    } else {
                        console.error('Error removing the product from the cart.');
                    }
                });
        }
    </script>




</body>

</html>