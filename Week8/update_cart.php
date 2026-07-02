<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        header('Location: cart.php?error=invalid_quantity');
        exit();
    }

    // Update quantity
    $update = mysqli_prepare($conn, "UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($update, "iii", $quantity, $cart_id, $_SESSION['user_id']);
    mysqli_stmt_execute($update);

    header('Location: cart.php?msg=updated');
} else {
    header('Location: cart.php');
}
exit();
?>