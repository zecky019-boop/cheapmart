<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$message = '';

if ($order_id > 0) {
    // Get order details
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $order_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);

    if (!$order) {
        header('Location: order_history.php');
        exit();
    }

    // Check if order is already paid
    if ($order['status'] == 'paid') {
        header('Location: order_confirmation.php?order_id=' . $order_id);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Simulate payment - update order status
        $update = mysqli_prepare($conn, "UPDATE orders SET status = 'paid' WHERE id = ?");
        mysqli_stmt_bind_param($update, "i", $order_id);
        mysqli_stmt_execute($update);

        header('Location: order_confirmation.php?order_id=' . $order_id);
        exit();
    }
} else {
    header('Location: order_history.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - CheapMart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .payment-container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        .amount { font-size: 36px; color: #ffa500; }
        .btn { background: #28a745; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 18px; }
        .btn:hover { background: #218838; }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="catalog.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php" onclick="return confirmLogout()">Logout</a></li>
        </ul>
    </nav>

    <div class="payment-container">
        <h1>Complete Payment</h1>
        <p>Order #<?php echo $order['order_number']; ?></p>
        <p class="amount">$<?php echo number_format($order['total_amount'], 2); ?></p>
        <p style="margin:20px 0; color:#666;">Payment method: <?php echo $order['payment_method']; ?></p>
        <p style="color:#999; font-size:14px; margin-bottom:20px;">This is a simulation. Click below to complete the payment.</p>
        <form method="POST">
            <button type="submit" class="btn">Pay Now</button>
        </form>
        <p style="margin-top:15px;"><a href="cart.php">Back to Cart</a></p>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>