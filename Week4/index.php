<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CheapMart - Best Deals</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="#">Products</a>
        <?php if (isset($_SESSION['user_email'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    <div class="container">
        <h1>Welcome to CheapMart</h1>
        <p>Your one-stop shop for affordable quality products.</p>
        <p>Explore our catalog and find amazing deals!</p>
    </div>
</body>
</html>