<?php
require_once 'includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, name, password, role FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_role'] = $row['role'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid email or password';
        }
    } else {
        $error = 'Invalid email or password';
    }
}
// HTML form remains same
?>