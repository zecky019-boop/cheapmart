<?php
require_once 'includes/config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = '<div class="success">Verification link resent (simulated).</div>';
}
?>
<!DOCTYPE html>
<html>
<head><title>Resend Verification</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <div class="container">
        <h2>Resend Verification Link</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="form-group"><label>Email:</label><input type="email" name="email" required></div>
            <button type="submit">Resend</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>