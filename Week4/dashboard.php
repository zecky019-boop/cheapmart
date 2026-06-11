<?php
require_once 'includes/config.php';

// Check if user is logged in
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
        <a href="dashboard.php">Home</a>
        <a href="#">Products</a>
        <a href="#">Cart</a>
        <?php if ($role == 'admin'): ?>
            <a href="#">Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        <p>Your role: <?php echo $role; ?></p>
        <p>This is your CheapMart dashboard. Here you can manage orders, view products, and more.</p>
        <?php if ($role == 'admin'): ?>
            <div class="success">You have admin privileges.</div>
        <?php endif; ?>
    </div>
</body>
</html>