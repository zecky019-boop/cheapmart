<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';

$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header('Location: admin_products.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $stock = intval($_POST['stock']);

    if (empty($name) || empty($price)) {
        $error = 'Name and price are required.';
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, description=?, price=?, category=?, stock=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssdsii", $name, $desc, $price, $category, $stock, $id);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: admin_products.php?msg=updated');
            exit();
        } else {
            $error = 'Database error: ' . mysqli_error($conn);
        }
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
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="catalog.php">Products</a></li>
            <li><a href="admin_products.php">Product List</a></li>
            <li><a href="logout.php" onclick="return confirmLogout()">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Edit Product</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

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
            <button type="submit" class="btn">Update Product</button>
        </form>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>