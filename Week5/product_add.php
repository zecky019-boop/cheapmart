<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);
    $category = $_POST['category'];
    $stock = intval($_POST['stock']);
    $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, category, stock) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssdsi", $name, $desc, $price, $category, $stock);
    if (mysqli_stmt_execute($stmt)) {
        $msg = '<div class="success">Product added successfully.</div>';
    } else {
        $msg = '<div class="error">Error: ' . mysqli_error($conn) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav><a href="admin_products.php">Back to Products</a> | <a href="logout.php">Logout</a></nav>
    <div class="container">
        <h2>Add New Product</h2>
        <?php echo $msg; ?>
        <form method="POST">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label>Price ($):</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            <div class="form-group">
                <label>Category:</label>
                <input type="text" name="category">
            </div>
            <div class="form-group">
                <label>Stock Quantity:</label>
                <input type="number" name="stock" value="0">
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
