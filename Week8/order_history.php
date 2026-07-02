<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - CheapMart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .order-container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f8f9fa; }
        .status { padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #ffc107; color: #333; }
        .status-paid { background: #28a745; color: white; }
        .status-shipped { background: #17a2b8; color: white; }
        .status-delivered { background: #007bff; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .btn { background: #ffa500; color: white; padding: 6px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 13px; }
        .btn:hover { background: #e69500; }
        .empty-orders { text-align: center; padding: 40px; }
        @media (max-width: 768px) { table { font-size: 13px; } th, td { padding: 8px; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="catalog.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php" onclick="return confirmLogout()">Logout</a></li>
        </ul>
    </nav>

    <div class="order-container">
        <h1>Order History</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $order['order_number']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><span class="status status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                            <td>
                                <a href="#" class="btn" style="background:#333;">View Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-orders">
                <h2>No orders yet</h2>
                <p>You haven't placed any orders.</p>
                <a href="catalog.php" class="btn" style="margin-top:15px;">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>