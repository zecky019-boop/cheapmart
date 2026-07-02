<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cart_id = isset($_GET['cart_id']) ? intval($_GET['cart_id']) : 0;

if ($cart_id > 0) {
    $delete = mysqli_prepare($conn, "DELETE FROM carts WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($delete, "ii", $cart_id, $_SESSION['user_id']);
    mysqli_stmt_execute($delete);
}

header('Location: cart.php?msg=removed');
exit();
?>