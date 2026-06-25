<?php
require_once 'includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, name, password, role, verified FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Check if user is verified
            if ($row['verified'] == 0) {
                $error = 'Please verify your email first. <a href="resend_verification.php?email=' . urlencode($email) . '">Resend verification link</a>';
            } elseif (password_verify($password, $row['password'])) {
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheapMart - Login</title>
    <style>
        /* Global Reset & Base Layout */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        /* Main Container Wrap */
        .container {
            background-color: #ffffff;
            width: 100%;
            max-width: 1200px;
            min-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #222222;
            padding: 15px 20px;
            border-radius: 4px;
            margin-bottom: 35px;
            width: 100%;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        .navbar a {
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #dddddd;
        }

        /* Content Headers */
        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 20px;
        }

        /* Error Message */
        .error-message {
            color: #cc0000;
            font-size: 15px;
            margin-bottom: 15px;
        }

        /* Form Structure Elements */
        form {
            width: 100%;
        }
        .form-group {
            margin-bottom: 20px;
            max-width: 320px;
        }
        label {
            display: block;
            font-size: 15px;
            color: #000000;
            margin-bottom: 8px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #d3d3d3;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ffa500;
        }

        /* Action Trigger Button Styling */
        .btn-login {
            background-color: #ffa500;
            color: #ffffff;
            border: none;
            padding: 10px 25px;
            font-size: 14px;
            font-weight: normal;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
            margin-bottom: 10px;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background-color: #e69500;
        }

        /* Forgot Password Link */
        .forgot-link {
            margin: 10px 0 15px 0;
        }
        .forgot-link a {
            color: #0066cc;
            text-decoration: underline;
            font-size: 14px;
        }
        .forgot-link a:hover {
            color: #0000aa;
        }

        /* Footer Links */
        .footer-link {
            font-size: 15px;
            color: #000000;
        }
        .footer-link a {
            color: #0000ee;
            text-decoration: underline;
        }
        .footer-link a:hover {
            color: #0000aa;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Navigation -->
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="catalog.php">Products</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>

        <!-- Section Header Title -->
        <h1>Login to CheapMart</h1>

        <!-- Error Message -->
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST">
            <!-- Email Input -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn-login">Login</button>

            <!-- NEW: Forgot Password Link -->
            <div class="forgot-link">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>

            <!-- Footer Links -->
            <div class="footer-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </form>
    </div>

</body>
</html>