<?php
require_once 'includes/config.php';
$message = '';
if (isset($_GET['token'])) {
    $message = '<div class="success">Token valid. Enter new password.</div>';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];
        if ($password === $confirm && strlen($password) >= 6) {
            $message = '<div class="success">Password reset successful! <a href="login.php">Login</a></div>';
        } else {
            $message = '<div class="error">Passwords do not match or too short.</div>';
        }
    }
} else {
    $message = '<div class="error">Invalid token.</div>';
}
?>
<!DOCTYPE html>
<html>
<head><title>Reset Password</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php echo $message; ?>
        <?php if (isset($_GET['token']) && !isset($_POST['password'])): ?>
        <form method="POST">
            <div class="form-group"><label>New Password:</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Confirm Password:</label><input type="password" name="confirm_password" required></div>
            <button type="submit">Reset</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>