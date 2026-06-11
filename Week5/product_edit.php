<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$id = intval($_GET['id']);
$msg = '';

// Fetch product details
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);
if (!$product) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);
    $category = $_POST['category'];
    $stock = intval($_POST['stock']);
    $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, description=?, price=?, category=?, stock=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssdsii", $name, $desc, $price, $category, $stock, $id);
    if (mysqli_stmt_execute($stmt)) {
        $msg = '<div class="success">Product updated successfully.</div>';
        // Refresh product data
        $stmt2 = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
        mysqli_stmt_bind_param($stmt2, "i", $id);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $product = mysqli_fetch_assoc($result2);
    } else {
        $msg = '<div class="error">Error: ' . mysqli_error($conn) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav><a href="admin_products.php">Back to Products</a> | <a href="logout.php">Logout</a></nav>
    <div class="container">
        <h2>Edit Product</h2>
        <?php echo $msg; ?>
        <form method="POST">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Price ($):</label>
                <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label>Category:</label>
                <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>">
            </div>
            <div class="form-group">
                <label>Stock Quantity:</label>
                <input type="number" name="stock" value="<?php echo $product['stock']; ?>">
            </div>
            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>