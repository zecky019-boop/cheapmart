<?php
require_once 'includes/config.php';

$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = mysqli_prepare($conn, "SELECT email FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $email = $row['email'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if (strlen($password) < 6) {
                $message = '<div class="error">Password must be at least 6 characters.</div>';
            } elseif ($password !== $confirm) {
                $message = '<div class="error">Passwords do not match.</div>';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $update = mysqli_prepare($conn, "UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE email = ?");
                mysqli_stmt_bind_param($update, "ss", $hashed, $email);
                if (mysqli_stmt_execute($update)) {
                    $delete = mysqli_prepare($conn, "DELETE FROM password_resets WHERE email = ?");
                    mysqli_stmt_bind_param($delete, "s", $email);
                    mysqli_stmt_execute($delete);

                    $message = '<div class="success">Password reset successful! <a href="login.php">Login now</a></div>';
                } else {
                    $message = '<div class="error">Failed to reset password.</div>';
                }
            }
        }
    } else {
        $message = '<div class="error">Invalid or expired token.</div>';
    }
} else {
    $message = '<div class="error">No token provided.</div>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php echo $message; ?>
        <?php if (isset($email) && !isset($_POST['password'])): ?>
        <form method="POST" onsubmit="return validateResetPassword()">
            <div class="form-group">
                <label>New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Reset</button>
        </form>
        <?php endif; ?>
        <p><a href="login.php">Back to Login</a></p>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>s