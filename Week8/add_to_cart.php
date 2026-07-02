<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

if ($product_id <= 0) {
    header('Location: catalog.php?error=invalid_product');
    exit();
}

// Check if product exists and has stock
$check = mysqli_prepare($conn, "SELECT id, stock FROM products WHERE id = ?");
mysqli_stmt_bind_param($check, "i", $product_id);
mysqli_stmt_execute($check);
$result = mysqli_stmt_get_result($check);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header('Location: catalog.php?error=product_not_found');
    exit();
}

if ($product['stock'] < $quantity) {
    header('Location: catalog.php?error=insufficient_stock');
    exit();
}

// Check if product already in cart
$check_cart = mysqli_prepare($conn, "SELECT id, quantity FROM carts WHERE user_id = ? AND product_id = ?");
mysqli_stmt_bind_param($check_cart, "ii", $user_id, $product_id);
mysqli_stmt_execute($check_cart);
$cart_result = mysqli_stmt_get_result($check_cart);

if ($row = mysqli_fetch_assoc($cart_result)) {
    // Update quantity
    $new_quantity = $row['quantity'] + $quantity;
    $update = mysqli_prepare($conn, "UPDATE carts SET quantity = ? WHERE id = ?");
    mysqli_stmt_bind_param($update, "ii", $new_quantity, $row['id']);
    mysqli_stmt_execute($update);
} else {
    // Insert new cart item
    $insert = mysqli_prepare($conn, "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($insert, "iii", $user_id, $product_id, $quantity);
    mysqli_stmt_execute($insert);
}

// Redirect to cart
header('Location: cart.php?msg=added');
exit();
?>