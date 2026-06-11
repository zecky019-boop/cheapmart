<?php
require_once 'includes/config.php';

$result = mysqli_query($conn, "SELECT * FROM products WHERE stock > 0 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>CheapMart - Product Catalog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="catalog.php">Products</a>
        <?php if (isset($_SESSION['user_email'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    <div class="container">
        <h2>Our Products</h2>
        <div class="product-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($row['description'], 0, 80)); ?>...</p>
                    <p><strong>$<?php echo $row['price']; ?></strong></p>
                    <p>Stock: <?php echo $row['stock']; ?></p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
