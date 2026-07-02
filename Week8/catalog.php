<?php
require_once 'includes/config.php';

// Fetch all products from database
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheapMart - Products</title>
    <style>
        /* Global Reset & Body Styling */
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

        /* Main Container */
        .wrapper {
            background-color: #ffffff;
            width: 100%;
            max-width: 1200px;
            min-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 25px;
        }

        /* Navigation Bar */
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

        /* Typography */
        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 25px;
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        /* Individual Product Card */
        .product-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 280px;
        }
        .product-title {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 12px;
        }
        .product-desc {
            font-size: 14px;
            color: #333333;
            line-height: 1.4;
            margin-bottom: 15px;
        }
        .product-price {
            font-size: 16px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 10px;
        }
        .product-stock {
            font-size: 14px;
            color: #333333;
            margin-bottom: 15px;
        }

        /* Add to Cart Button */
        .btn-add {
            background-color: #ffa500;
            color: #ffffff;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 4px;
            cursor: pointer;
            width: fit-content;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-add:hover {
            background-color: #e69500;
        }
        .btn-add-outline {
            background-color: #333333;
        }
        .btn-add-outline:hover {
            background-color: #555555;
        }

        /* No Products Message */
        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #777;
            font-size: 18px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            .products-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 576px) {
            .products-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <!-- Top Dashboard Navigation -->
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="catalog.php">Products</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Page Header -->
        <h1>Our Products</h1>

        <!-- Products Grid -->
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="products-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <div>
                            <div class="product-title"><?php echo htmlspecialchars($row['name']); ?></div>
                            <div class="product-desc"><?php echo htmlspecialchars($row['description']); ?></div>
                        </div>
                        <div>
                            <div class="product-price">$<?php echo number_format($row['price'], 2); ?></div>
                            <div class="product-stock">Stock: <?php echo $row['stock']; ?></div>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="add_to_cart.php?product_id=<?php echo $row['id']; ?>" class="btn-add">Add to Cart</a>
                            <?php else: ?>
                                <a href="login.php" class="btn-add btn-add-outline">Add to Cart</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <div class="no-products">
                    <p>No products available at the moment.</p>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                        <p style="margin-top: 10px;"><a href="product_add.php" class="btn-add">Add Your First Product</a></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>