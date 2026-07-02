<?php
require_once 'includes/config.php';

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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="catalog.php">Products</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <?php if ($role == 'admin'): ?>
                    <li><a href="admin_products.php">Manage Products</a></li>
                <?php endif; ?>
                <li><a href="logout.php" onclick="return confirmLogout()">Logout</a></li>
            </ul>
        </nav>

        <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>

        <div class="role-text">Your role: <?php echo $role; ?></div>

        <div class="welcome-desc">
            This is your CheapMart dashboard. Here you can manage orders, view products, and more.
        </div>

        <?php if ($role == 'admin'): ?>
            <div class="privilege-container">
                <span class="admin-tag">You have admin privileges.</span>
                <a href="admin_products.php" class="inline-link">Manage products</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>