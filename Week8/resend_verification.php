<?php
require_once 'includes/config.php';

$message = '';
$email = isset($_GET['email']) ? $_GET['email'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND verified = 0");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $new_token = bin2hex(random_bytes(32));
        $update = mysqli_prepare($conn, "UPDATE users SET verification_token = ? WHERE email = ?");
        mysqli_stmt_bind_param($update, "ss", $new_token, $email);
        mysqli_stmt_execute($update);

        $message = '<div class="success">New verification link sent! <a href="verify_email.php?token=' . $new_token . '">Click here to verify</a></div>';
    } else {
        $message = '<div class="error">Email not found or already verified.</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Resend Verification</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Resend Verification Link</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <button type="submit" class="btn">Send Verification Link</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>