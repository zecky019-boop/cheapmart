<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $order_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);

    if (!$order) {
        header('Location: order_history.php');
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
    <title>Order Confirmation - CheapMart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .confirmation-container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        .success-icon { font-size: 80px; color: #28a745; }
        .btn { background: #ffa500; color: white; padding: 10px 25px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #e69500; }
        .order-details { text-align: left; background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0; }
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

    <div class="confirmation-container">
        <div class="success-icon">✅</div>
        <h1>Order Placed Successfully!</h1>
        <p>Thank you for your order. You will receive a confirmation email shortly.</p>

        <div class="order-details">
            <p><strong>Order Number:</strong> <?php echo $order['order_number']; ?></p>
            <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
            <p><strong>Order Date:</strong> <?php echo date('F d, Y h:i A', strtotime($order['created_at'])); ?></p>
        </div>

        <div style="margin-top:20px; display:flex; gap:10px; flex-wrap:wrap; justify-content:center;">
            <a href="catalog.php" class="btn">Continue Shopping</a>
            <a href="order_history.php" class="btn" style="background:#333;">View Orders</a>
        </div>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>