<?php
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($fullname) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match';
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);
        if (mysqli_stmt_num_rows($check) > 0) {
            $error = 'Email already registered. Please login.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $verification_token = bin2hex(random_bytes(32));
            $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, verification_token, verified) VALUES (?, ?, ?, ?, 0)");
            mysqli_stmt_bind_param($stmt, "ssss", $fullname, $email, $hashed, $verification_token);
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Registration successful! Please verify your email using the link below.<br>';
                $success .= '<a href="verify_email.php?token=' . $verification_token . '" style="color: #ffa500; font-weight: bold;">Click here to verify your email</a>';
            } else {
                $error = 'Database error. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheapMart - Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="catalog.php">Products</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>

        <h1>Create CheapMart Account</h1>

        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" id="fullname" name="fullname" onkeyup="livePreview()" required>
                <div id="livePreview" class="preview"></div>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" id="password" name="password" onkeyup="checkPasswordStrength()" required>
                <div id="passwordStrength" class="password-strength"></div>
            </div>

            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-register">Register</button>

            <div class="footer-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </form>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>