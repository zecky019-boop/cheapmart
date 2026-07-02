<?php
// Include your config file
require_once 'includes/config.php';

// Fetch products from the database
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Product List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f4f4; color: #333; }

        /* Navbar */
        nav { background-color: #333; padding: 15px 20px; }
        nav a { color: white; text-decoration: none; margin-right: 20px; font-weight: bold; }

        .container { max-width: 1100px; margin: 20px auto; padding: 30px; background: white; border: 1px solid #ddd; }
        
        h2 { margin-bottom: 20px; }

        /* Add Button */
        .btn-add { 
            background-color: #f39c12; 
            color: white; 
            padding: 10px 15px; 
            text-decoration: none; 
            border-radius: 3px; 
            display: inline-block; 
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
        th { font-weight: bold; }
        
        /* Action Links */
        .actions a { color: #007bff; text-decoration: underline; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h2>Product List</h2>
        <a href="product_add.php" class="btn-add">Add New Product</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>$<?= number_format($row['price'], 2) ?></td>
                        <td><?= (int)$row['stock'] ?></td>
                        <td class="actions">
                            <a href="product_edit.php?id=<?= $row['id'] ?>">Edit</a> | 
                            <a href="product_delete.php?id=<?= $row['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>