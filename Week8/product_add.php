<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['product_name']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;

    if (empty($name) || empty($price)) {
        $error = 'Product Name and Price are required.';
    } elseif ($price <= 0) {
        $error = 'Price must be greater than 0.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, category, stock) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssdsi", $name, $desc, $price, $category, $stock);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: admin_products.php?msg=added');
            exit();
        } else {
            $error = 'Database error: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheapMart - Add New Product</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <nav class="navbar">
            <ul>
                <li><a href="admin_products.php">Back to Products</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <h1>Add New Product</h1>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>

            <div class="form-group">
                <label>Price ($):</label>
                <input type="number" step="0.01" id="price" name="price" required>
            </div>

            <div class="form-group">
                <label>Category:</label>
                <input type="text" id="category" name="category">
            </div>

            <div class="form-group">
                <label>Stock Quantity:</label>
                <input type="number" id="stock" name="stock" value="0">
            </div>

            <button type="submit" class="btn-submit">Add Product</button>
        </form>
    </div>

</body>
</html>