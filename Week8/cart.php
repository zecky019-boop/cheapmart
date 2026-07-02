<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$total = 0;

// Get cart items
$stmt = mysqli_prepare($conn, "
    SELECT c.id, c.quantity, p.id as product_id, p.name, p.price, p.image, p.stock 
    FROM carts c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Calculate total
while ($row = mysqli_fetch_assoc($result)) {
    $total += $row['price'] * $row['quantity'];
}

// Reset result pointer for display
mysqli_stmt_execute($stmt);
$result2 = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - CheapMart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f8f9fa; }
        .qty-input { width: 60px; padding: 5px; text-align: center; }
        .btn { background: #ffa500; color: white; padding: 8px 18px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #e69500; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .total-row { font-weight: bold; font-size: 18px; }
        .empty-cart { text-align: center; padding: 40px; }
        .cart-actions { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
        .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
        @media (max-width: 768px) {
            table { font-size: 13px; }
            th, td { padding: 8px; }
            .qty-input { width: 50px; }
        }
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

    <div class="cart-container">
        <h1>Shopping Cart</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($result2) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($result2)): ?>
                        <tr>
                            <td>
                                <img src="images/<?php echo $item['image'] ?: 'default.jpg'; ?>" class="product-image">
                            </td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="POST" action="update_cart.php">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" class="qty-input">
                                    <button type="submit" class="btn" style="padding:5px 10px; font-size:12px;">Update</button>
                                </form>
                            </td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <a href="remove_from_cart.php?cart_id=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Remove this item?')">Remove</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="4" align="right"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="cart-actions">
                <a href="catalog.php" class="btn" style="background:#333;">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Browse our products and add items to your cart.</p>
                <a href="catalog.php" class="btn" style="margin-top:15px;">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>