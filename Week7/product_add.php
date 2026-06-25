<?php
require_once 'includes/config.php';

// Check if user is logged in and is admin
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

        /* Top Navigation Bar */
        .navbar {
            background-color: #222222;
            padding: 15px 20px;
            border-radius: 4px;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            align-items: center;
        }
        .navbar a {
            color: #ffffff;
            text-decoration: none;
            font-size: 15px;
        }
        .navbar a:hover {
            color: #dddddd;
        }
        .navbar .divider {
            color: #ffffff;
            margin: 0 10px;
            font-size: 15px;
        }

        /* Content Headers */
        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 20px;
        }

        /* Success Message Alert Banner */
        .success-message {
            color: #008000;
            font-size: 15px;
            margin-bottom: 15px;
        }
        .error-message {
            color: #cc0000;
            font-size: 15px;
            margin-bottom: 15px;
        }

        /* Form Structure Elements */
        .form-group {
            margin-bottom: 20px;
            max-width: 320px; /* Constrains inputs to match visual width */
        }
        label {
            display: block;
            font-size: 15px;
            color: #000000;
            margin-bottom: 8px;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #d3d3d3;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
            background-color: #ffffff;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            border-color: #a0a0a0;
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* Submit Button */
        .btn-submit {
            background-color: #ffa500;
            color: #ffffff;
            border: none;
            padding: 10px 25px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 5px;
        }
        .btn-submit:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Top Horizontal Navigation -->
        <nav class="navbar">
            <ul>
                <li><a href="admin_products.php">Back to Products</a></li>
                <span class="divider">|</span>
                <li><a href="dashboard.php">Dashboard</a></li>
                <span class="divider">|</span>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Section Header Title -->
        <h1>Add New Product</h1>

        <!-- Status Messages -->
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Submission Interaction Structure Matrix -->
        <form action="" method="POST">
            <!-- Product Name Input -->
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>
            </div>

            <!-- Description Input Area -->
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>

            <!-- Pricing Input -->
            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" step="0.01" id="price" name="price" required>
            </div>

            <!-- Category Classification Selector -->
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category">
            </div>

            <!-- Stock Quantity Input -->
            <div class="form-group">
                <label for="stock">Stock Quantity:</label>
                <input type="number" id="stock" name="stock" value="0">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">Add Product</button>
        </form>
    </div>

</body>
</html>