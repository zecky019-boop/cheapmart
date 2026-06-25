<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$name = $_SESSION['user_name'];
$email = $_SESSION['user_email'];
$role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheapMart - Dashboard</title>
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

        /* Top Horizontal Dashboard Navigation Bar */
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
            font-weight: normal;
        }
        .navbar a:hover {
            color: #dddddd;
        }

        /* Dashboard Typography Content */
        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 20px;
        }
        .role-text {
            font-size: 16px;
            color: #000000;
            margin-bottom: 15px;
        }
        .welcome-desc {
            font-size: 16px;
            color: #000000;
            margin-bottom: 20px;
        }

        /* Authorization Privilege Strings */
        .privilege-container {
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .admin-tag {
            color: #008000;
        }
        .inline-link {
            color: #800080;
            text-decoration: underline;
        }
        .inline-link:hover {
            color: #550055;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Top Horizontal Navigation -->
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="catalog.php">Products</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>  <!-- NEW: Profile Link -->
                <?php if ($role == 'admin'): ?>
                    <li><a href="admin_products.php">Manage Products</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Main Heading Statement -->
        <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>

        <!-- Role Parameter Indicator Label -->
        <div class="role-text">Your role: <?php echo $role; ?></div>

        <!-- Explanatory Dashboard Body Content Text -->
        <div class="welcome-desc">
            This is your CheapMart dashboard. Here you can manage orders, view products, and more.
        </div>

        <!-- Administrative Authorization Context String Links -->
        <?php if ($role == 'admin'): ?>
            <div class="privilege-container">
                <span class="admin-tag">You have admin privileges.</span>
                <a href="admin_products.php" class="inline-link">Manage products</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>