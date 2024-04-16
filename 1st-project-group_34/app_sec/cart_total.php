<?php
session_start();
$con = mysqli_connect('localhost', 'root', '', 'sio');

if (isset($_SESSION['user_id']) && isset($_SESSION['cart_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart_id = $_SESSION['cart_id'];
    $sql = "SELECT SUM(quantidade*price) as total FROM carts JOIN cart_user ON cart_id=cart_user.id JOIN products ON carts.product_id=products.id WHERE cart_user.id='$cart_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];
    $sql = "UPDATE cart_user SET total = '$total' WHERE id = '$cart_id'";
    mysqli_query($con, $sql);
}


?>