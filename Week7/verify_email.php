<?php
require_once 'includes/config.php';
$message = isset($_GET['token']) ? '<div class="success">Email verified successfully! <a href="login.php">Login</a></div>' : '<div class="error">Invalid token.</div>';
?>
<!DOCTYPE html>
<html>
<head><title>Verify Email</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <div class="container">
        <h2>Email Verification</h2>
        <?php echo $message; ?>
    </div>
</body>
</html>
