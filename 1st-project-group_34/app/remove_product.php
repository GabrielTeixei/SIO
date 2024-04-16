<?php
session_start();
$con = mysqli_connect('localhost', 'root', '', 'sio');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $cartId = $_SESSION['cart_id'];

    // Delete the product from the cart
    $sql = "DELETE FROM carts WHERE cart_id = $cartId AND product_id = $productId";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
