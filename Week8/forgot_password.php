<?php
require_once 'includes/config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $insert = mysqli_prepare($conn, "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($insert, "sss", $email, $token, $expiry);
        mysqli_stmt_execute($insert);

        $update = mysqli_prepare($conn, "UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
        mysqli_stmt_bind_param($update, "sss", $token, $expiry, $email);
        mysqli_stmt_execute($update);

        $message = '<div class="success">A password reset link has been sent to your email (simulated).<br>
                    <a href="reset_password.php?token=' . $token . '">Click here to reset password</a></div>';
    } else {
        $message = '<div class="error">Email not found.</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Forgot Password</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" class="btn">Send Reset Link</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>