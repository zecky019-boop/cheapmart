<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header('Location: admin_products.php');
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Products</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="admin_products.php">Products</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h2>Product List</h2>
        <a href="product_add.php" class="btn">Add New Product</a>
        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>$<?= $row['price'] ?></td>
                        <td><?= $row['stock'] ?></td>
                        <td>
                            <a href="product_edit.php?id=<?= $row['id'] ?>">Edit</a> |
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
