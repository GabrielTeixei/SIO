<?php
session_start();
$con = mysqli_connect('localhost', 'root', '', 'sio');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    // Valide os dados, verifique se o usuário possui esse produto no carrinho, etc.

    // Atualize a quantidade no banco de dados
    $sql = "UPDATE carts SET quantidade = $new_quantity WHERE cart_id = {$_SESSION['cart_id']} AND product_id = $product_id";
    
    if (mysqli_query($con, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    // Lida com outros métodos HTTP, se necessário
    echo json_encode(['success' => false]);
}

?>