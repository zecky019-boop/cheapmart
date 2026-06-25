<?php
require_once 'includes/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header('Location: admin_products.php?msg=deleted');
    exit();
}

// Fetch all products from database
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheapMart - Admin Products</title>
    <style>
        /* Global Reset & Base Layout */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        /* Main Container Wrap */
        .container {
            background-color: #ffffff;
            width: 100%;
            max-width: 1200px;
            min-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 25px;
        }

        /* Dashboard Top Navigation Bar */
        .navbar {
            background-color: #222222;
            padding: 15px 20px;
            border-radius: 4px;
            margin-bottom: 35px;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        .navbar a {
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #dddddd;
        }

        /* Content Headers */
        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 20px;
        }

        /* Success/Error Messages */
        .message {
            padding: 12px 18px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Add New Product Button styling */
        .btn-add {
            background-color: #ffa500;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
            margin-bottom: 30px;
            transition: background 0.2s;
        }
        .btn-add:hover {
            background-color: #e69500;
        }

        /* Structured Data Table Layout */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 15px;
        }
        .product-table th, 
        .product-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #e0e0e0;
        }

        /* Table Headers Styling */
        .product-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #000000;
        }

        /* Table Row Content Colors */
        .product-table td {
            color: #333333;
        }

        /* Inline Action Hyperlinks styling - BOTH EDIT AND DELETE IN BLUE */
        .action-links a {
            color: #0000ee;
            text-decoration: underline;
        }
        .action-links a:hover {
            color: #0000aa;
        }
        .divider {
            color: #333333;
            margin: 0 4px;
        }

        .empty-row td {
            text-align: center;
            padding: 40px 20px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Top Dashboard Navigation -->
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="catalog.php">Products</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Section Header -->
        <h1>Product List</h1>

        <!-- Display Success/Error Messages -->
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'added'): ?>
                <div class="message success"> Product added successfully.</div>
            <?php elseif ($_GET['msg'] == 'updated'): ?>
                <div class="message success"> Product updated successfully.</div>
            <?php elseif ($_GET['msg'] == 'deleted'): ?>
                <div class="message success"> Product deleted successfully.</div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Administrative Add Button Action Trigger -->
        <a href="product_add.php" class="btn-add">Add New Product</a>

        <!-- Main Product Dashboard Data Table Matrix -->
        <table class="product-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 37%;">Name</th>
                    <th style="width: 20%;">Price</th>
                    <th style="width: 15%;">Stock</th>
                    <th style="width: 20%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['stock']; ?></td>
                            <td class="action-links">
                                <a href="product_edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                                <span class="divider">|</span>
                                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr class="empty-row">
                        <td colspan="5">No products found. <a href="product_add.php" style="color: #0066cc;">Add your first product</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>