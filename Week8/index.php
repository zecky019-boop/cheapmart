<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheapMart - Home</title>
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
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #222222;
            padding: 15px 20px;
            border-radius: 4px;
            margin-bottom: 35px;
            width: 100%;
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

        /* Hero Section */
        .hero {
            width: 100%;
            padding: 40px 0;
        }
        .hero h1 {
            font-size: 42px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 15px;
        }
        .hero .tagline {
            font-size: 20px;
            color: #333333;
            margin-bottom: 10px;
        }
        .hero .description {
            font-size: 16px;
            color: #555555;
            line-height: 1.8;
            max-width: 600px;
        }

        .greeting {
            font-size: 18px;
            color: #333333;
            margin-top: 20px;
            font-weight: bold;
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #ffa500;
            width: 100%;
            max-width: 500px;
        }

        /* Features Section */
        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 40px;
            width: 100%;
        }
        .feature-box {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 25px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }
        .feature-box h3 {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 10px;
        }
        .feature-box p {
            font-size: 14px;
            color: #555555;
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .features {
                grid-template-columns: 1fr;
            }
            .hero h1 {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Navigation -->
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="catalog.php">Products</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                        <li><a href="admin_products.php">Manage Products</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Hero Section -->
        <div class="hero">
            <h1>Welcome to CheapMart</h1>
            <div class="tagline">Your one-stop shop for affordable quality products.</div>
            <div class="description">
                Explore our wide range of products at unbeatable prices. 
                From electronics to accessories, we have everything you need.
            </div>

            <?php if (isset($_SESSION['user_name'])): ?>
                <div class="greeting">
                    Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! 
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                        <span style="color: #008000; font-weight: normal;">(Admin)</span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="greeting">
                    <a href="register.php" style="color: #ffa500; text-decoration: none;">Create an account</a> 
                    or <a href="login.php" style="color: #ffa500; text-decoration: none;">login</a> to start shopping!
                </div>
            <?php endif; ?>
        </div>

        <!-- Features Section -->
        <div class="features">
            <div class="feature-box">
                <h3>🛒 Wide Selection</h3>
                <p>Browse through hundreds of products across multiple categories.</p>
            </div>
            <div class="feature-box">
                <h3>💰 Best Prices</h3>
                <p>Get the best deals and discounts on all your favorite items.</p>
            </div>
            <div class="feature-box">
                <h3>🚚 Fast Delivery</h3>
                <p>Quick and reliable delivery right to your doorstep.</p>
            </div>
        </div>
    </div>

</body>
</html>