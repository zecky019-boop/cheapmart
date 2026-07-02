<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';
$total = 0;

// Get user data
$stmt = mysqli_prepare($conn, "SELECT name, email, phone FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Get cart items
$cart_stmt = mysqli_prepare($conn, "
    SELECT c.id, c.quantity, p.id as product_id, p.name, p.price 
    FROM carts c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
mysqli_stmt_bind_param($cart_stmt, "i", $user_id);
mysqli_stmt_execute($cart_stmt);
$cart_result = mysqli_stmt_get_result($cart_stmt);

$cart_items = [];
while ($item = mysqli_fetch_assoc($cart_result)) {
    $cart_items[] = $item;
    $total += $item['price'] * $item['quantity'];
}

if (empty($cart_items)) {
    header('Location: cart.php?error=empty_cart');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $payment_method = $_POST['payment_method'];

    if (empty($address) || empty($phone)) {
        $error = 'Please fill in all fields.';
    } else {
        // Generate order number
        $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());

        // Begin transaction
        mysqli_begin_transaction($conn);

        try {
            // Insert order
            $insert_order = mysqli_prepare($conn, "
                INSERT INTO orders (user_id, order_number, total_amount, payment_method, shipping_address, shipping_phone, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending')
            ");
            mysqli_stmt_bind_param($insert_order, "isdsss", $user_id, $order_number, $total, $payment_method, $address, $phone);
            mysqli_stmt_execute($insert_order);
            $order_id = mysqli_insert_id($conn);

            // Insert order items
            $insert_item = mysqli_prepare($conn, "
                INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                VALUES (?, ?, ?, ?, ?)
            ");
            foreach ($cart_items as $item) {
                mysqli_stmt_bind_param($insert_item, "iisid", $order_id, $item['product_id'], $item['name'], $item['quantity'], $item['price']);
                mysqli_stmt_execute($insert_item);

                // Update product stock
                $update_stock = mysqli_prepare($conn, "UPDATE products SET stock = stock - ? WHERE id = ?");
                mysqli_stmt_bind_param($update_stock, "ii", $item['quantity'], $item['product_id']);
                mysqli_stmt_execute($update_stock);
            }

            // Clear cart
            $clear_cart = mysqli_prepare($conn, "DELETE FROM carts WHERE user_id = ?");
            mysqli_stmt_bind_param($clear_cart, "i", $user_id);
            mysqli_stmt_execute($clear_cart);

            // Commit transaction
            mysqli_commit($conn);

            // Redirect to payment simulation
            header('Location: payment_simulate.php?order_id=' . $order_id);
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = 'There was a problem processing your order. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - CheapMart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .checkout-container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .order-summary { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], textarea, select { width: 100%; max-width: 400px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #ffa500; color: white; padding: 10px 25px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #e69500; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        @media (max-width: 768px) { .checkout-container { padding: 15px; } }
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

    <div class="checkout-container">
        <h1>Checkout</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="order-summary">
            <h3>Order Summary</h3>
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold; border-top:2px solid #333;">
                    <td colspan="3" align="right">Total:</td>
                    <td>$<?php echo number_format($total, 2); ?></td>
                </tr>
            </table>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Shipping Address:</label>
                <textarea name="address" rows="3" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label>Payment Method:</label>
                <select name="payment_method">
                    <option value="simulated">Simulated Payment</option>
                    <option value="mpesa">M-Pesa (Simulated)</option>
                    <option value="card">Credit Card (Simulated)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Place Order</button>
            <a href="cart.php" class="btn" style="background:#333;">Back to Cart</a>
        </form>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>