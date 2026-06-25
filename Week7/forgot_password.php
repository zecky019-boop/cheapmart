<?php
require_once 'includes/config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    // Simple: just show a message
    $message = '<div class="success">Reset link sent to your email (simulated).</div>';
}
?>
<!DOCTYPE html>
<html>
<head><title>Forgot Password</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <nav><a href="index.php">Home</a><a href="login.php">Login</a></nav>
    <div class="container">
        <h2>Forgot Password</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="form-group"><label>Email:</label><input type="email" name="email" required></div>
            <button type="submit">Send Reset Link</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>