<?php
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
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
            $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed);
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Registration successful! You can now <a href="login.php">login</a>.';
            } else {
                $error = 'Database error. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CheapMart - Register</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function validateForm() {
            let password = document.getElementById('password').value;
            let confirm = document.getElementById('confirm_password').value;
            let email = document.getElementById('email').value;
            let name = document.getElementById('name').value;

            if (name === '' || email === '' || password === '') {
                alert('All fields are required');
                return false;
            }
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Invalid email format');
                return false;
            }
            if (password.length < 6) {
                alert('Password must be at least 6 characters');
                return false;
            }
            if (password !== confirm) {
                alert('Passwords do not match');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Create CheapMart Account</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
