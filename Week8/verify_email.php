<?php
require_once 'includes/config.php';

$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE verification_token = ? AND verified = 0");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $update = mysqli_prepare($conn, "UPDATE users SET verified = 1, verification_token = NULL WHERE id = ?");
        mysqli_stmt_bind_param($update, "i", $row['id']);

        if (mysqli_stmt_execute($update)) {
            $message = '<div class="success">Email verified successfully! <a href="login.php">Login now</a></div>';
        } else {
            $message = '<div class="error">Verification failed.</div>';
        }
    } else {
        $message = '<div class="error">Invalid or expired token. <a href="resend_verification.php">Request new one</a></div>';
    }
} else {
    $message = '<div class="error">No token provided.</div>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verify Email</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Email Verification</h2>
        <?php echo $message; ?>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
