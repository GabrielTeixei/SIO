<?php
// Inicie a sessão e conecte ao banco de dados
session_start();
$con = mysqli_connect('localhost', 'root', '', 'sio');

if (!$con) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Verifique se o usuário está logado e tem um carrinho ativo
if (isset($_SESSION['user_id']) && isset($_SESSION['cart_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart_id = $_SESSION['cart_id'];

    // Consulta para obter a quantidade de itens no carrinho
    $sql = "SELECT SUM(quantidade) AS quantity FROM carts WHERE cart_id = '$cart_id'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $quantity = (int)$row['quantity'];

        // Responda com um JSON contendo a quantidade
        echo json_encode(array('success' => true, 'quantity' => $quantity));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Falha na consulta.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Usuário não logado ou carrinho inexistente.'));
}

// Feche a conexão com o banco de dados
mysqli_close($con);
?>
