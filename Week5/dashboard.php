<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['user_role'];
$name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>CheapMart - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="catalog.php">Products</a>
        <a href="dashboard.php">Dashboard</a>
        <?php if ($role == 'admin'): ?>
            <a href="admin_products.php">Manage Products</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        <p>Your role: <?php echo $role; ?></p>
        <p>This is your CheapMart dashboard. Here you can manage orders, view products, and more.</p>
        <?php if ($role == 'admin'): ?>
            <div class="success">You have admin privileges. <a href="admin_products.php">Manage products</a></div>
        <?php endif; ?>
    </div>
</body>
</html>