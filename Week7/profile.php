<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch user data
$stmt = mysqli_prepare($conn, "SELECT name, email, phone FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    
    $update = mysqli_prepare($conn, "UPDATE users SET name = ?, phone = ? WHERE id = ?");
    mysqli_stmt_bind_param($update, "ssi", $name, $phone, $user_id);
    if (mysqli_stmt_execute($update)) {
        $_SESSION['user_name'] = $name;
        $message = '<div class="success">Profile updated successfully!</div>';
        // Refresh user data
        $stmt = mysqli_prepare($conn, "SELECT name, email, phone FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    } else {
        $message = '<div class="error">Failed to update profile.</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CheapMart - Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="catalog.php">Products</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
    
    <div class="container">
        <h2>My Profile</h2>
        <?php echo $message; ?>
        
        <div class="profile-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?: 'Not set'); ?></p>
        </div>
        
        <h3>Update Profile</h3>
        <form method="POST">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>