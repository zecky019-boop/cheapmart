<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!password_verify($current, $row['password'])) {
        $message = '<div class="error">Current password is incorrect.</div>';
    } elseif (strlen($new) < 6) {
        $message = '<div class="error">New password must be at least 6 characters.</div>';
    } elseif ($new !== $confirm) {
        $message = '<div class="error">Passwords do not match.</div>';
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "si", $hashed, $user_id);
        if (mysqli_stmt_execute($update)) {
            $message = '<div class="success">Password changed successfully!</div>';
        } else {
            $message = '<div class="error">Failed to change password.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php" onclick="return confirmLogout()">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Change Password</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="form-group">
                <label>Current Password:</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>New Password:</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Change Password</button>
        </form>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>