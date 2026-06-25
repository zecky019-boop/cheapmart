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
        // Check if email already exists
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);
        if (mysqli_stmt_num_rows($check) > 0) {
            $error = 'Email already registered. Please login.';
        } else {
            // Hash the password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            
            // Generate verification token
            $verification_token = bin2hex(random_bytes(32));
            
            // Insert user with verification_token and verified = 0
            $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, verification_token, verified) VALUES (?, ?, ?, ?, 0)");
            mysqli_stmt_bind_param($stmt, "ssss", $fullname, $email, $hashed, $verification_token);
            
            if (mysqli_stmt_execute($stmt)) {
                // Registration successful - show verification link
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
            background-color: #f8d7da;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            width: 100%;
            max-width: 500px;
        }

        /* Success Message String Notification */
        .success-message {
            color: #155724;
            background-color: #d4edda;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
            width: 100%;
            max-width: 500px;
        }
        .success-message a {
            color: #ffa500;
            font-weight: bold;
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
            font-weight: normal;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #d3d3d3;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
            background-color: #ffffff;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ffa500;
        }

        /* Live Preview */
        .preview {
            color: #666;
            font-style: italic;
            margin-top: 5px;
            font-size: 14px;
        }

        /* Password Strength */
        .password-strength {
            font-size: 14px;
            margin-top: 5px;
        }

        /* Action Trigger Button Styling */
        .btn-register {
            background-color: #ffa500;
            color: #ffffff;
            border: none;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: normal;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
            margin-bottom: 20px;
            transition: background 0.2s;
        }
        .btn-register:hover {
            background-color: #e69500;
        }

        /* Footer Navigation Links */
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
        <h1>Create CheapMart Account</h1>

        <!-- Error Message -->
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Submission Interaction Structure Matrix -->
        <form method="POST" onsubmit="return validateForm()">
            <!-- Full Name Input -->
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" onkeyup="livePreview()" required>
                <div id="livePreview" class="preview"></div>
            </div>

            <!-- Email Input -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" onkeyup="checkPasswordStrength()" required>
                <div id="passwordStrength" class="password-strength"></div>
            </div>

            <!-- Confirm Password Input -->
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <!-- Action Trigger Button -->
            <button type="submit" class="btn-register">Register</button>

            <!-- Bottom Redirect Link -->
            <div class="footer-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </form>
    </div>

    <script>
        // Form Validation
        function validateForm() {
            let fullname = document.getElementById('fullname').value.trim();
            let email = document.getElementById('email').value.trim();
            let password = document.getElementById('password').value;
            let confirm = document.getElementById('confirm_password').value;

            if (fullname === '' || email === '' || password === '') {
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

        // Live Preview
        function livePreview() {
            let fullname = document.getElementById('fullname').value.trim();
            let preview = document.getElementById('livePreview');
            if (fullname !== '') {
                preview.innerHTML = 'Preview: Welcome, ' + fullname + '!';
            } else {
                preview.innerHTML = '';
            }
        }

        // Password Strength Checker
        function checkPasswordStrength() {
            let password = document.getElementById('password').value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;

            let msg = document.getElementById('passwordStrength');

            if (password === '') {
                msg.innerHTML = '';
                msg.style.color = '';
            } else if (strength < 3) {
                msg.innerHTML = 'Weak password. Use 8+ chars, uppercase, number, special char.';
                msg.style.color = 'red';
            } else if (strength < 5) {
                msg.innerHTML = 'Medium password.';
                msg.style.color = 'orange';
            } else {
                msg.innerHTML = 'Strong password!';
                msg.style.color = 'green';
            }
        }
    </script>

</body>
</html>