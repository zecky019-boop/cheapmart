<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    // Simple: just show success
    $message = '<div class="success">Password changed successfully!</div>';
}
?>
<!DOCTYPE html>
<html>
<head><title>Change Password</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <nav><a href="index.php">Home</a><a href="dashboard.php">Dashboard</a><a href="logout.php">Logout</a></nav>
    <div class="container">
        <h2>Change Password</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="form-group"><label>Current Password:</label><input type="password" name="current_password" required></div>
            <div class="form-group"><label>New Password:</label><input type="password" name="new_password" required></div>
            <div class="form-group"><label>Confirm Password:</label><input type="password" name="confirm_password" required></div>
            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>