<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// Fetch user data from database
$stmt = mysqli_prepare($conn, "SELECT name, email, phone FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    
    // Validate input
    if (empty($name)) {
        $message = 'Name cannot be empty.';
        $message_type = 'error';
    } else {
        // Update database
        $update = mysqli_prepare($conn, "UPDATE users SET name = ?, phone = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "ssi", $name, $phone, $user_id);
        if (mysqli_stmt_execute($update)) {
            $_SESSION['user_name'] = $name;
            $message = 'Profile updated successfully!';
            $message_type = 'success';
            // Refresh user data
            $stmt = mysqli_prepare($conn, "SELECT name, email, phone FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
        } else {
            $message = 'Failed to update profile. Please try again.';
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CheapMart</title>
    <style>
        /* General Styles */
        body { 
            font-family: sans-serif; 
            margin: 0; 
            background-color: #f4f4f4; 
            color: #333; 
        }

        /* Navbar */
        nav { 
            background-color: #333; 
            padding: 15px 20px; 
            color: white; 
            overflow: hidden;
        }
        nav a { 
            color: white; 
            text-decoration: none; 
            margin-right: 20px; 
            font-weight: bold; 
            display: inline-block;
        }
        nav a:hover {
            color: #ddd;
        }

        /* Container */
        .container { 
            max-width: 800px; 
            margin: 40px auto; 
            padding: 30px; 
            background: white; 
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 { 
            margin-bottom: 20px; 
            font-weight: normal;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        hr { 
            border: 0; 
            border-top: 1px solid #eee; 
            margin: 20px 0; 
        }

        /* Message Styles */
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid transparent;
        }
        .message.success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .message.error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        /* Profile Info List */
        .profile-info p { 
            margin: 10px 0; 
            font-weight: bold; 
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .profile-info p:last-child {
            border-bottom: none;
        }

        /* Form Styling */
        .update-form { 
            margin-top: 30px; 
        }
        .form-group { 
            margin-bottom: 15px; 
        }
        label { 
            display: block; 
            font-weight: bold; 
            margin-bottom: 5px; 
        }
        input[type="text"],
        input[type="tel"] { 
            width: 100%; 
            max-width: 400px;
            padding: 10px; 
            border: 1px solid #ccc; 
            box-sizing: border-box; 
            border-radius: 4px;
        }
        input[type="text"]:focus,
        input[type="tel"]:focus {
            border-color: #ff6600;
            outline: none;
        }

        /* Button */
        .btn-update { 
            background-color: #ff6600; 
            color: white; 
            border: none; 
            padding: 10px 25px; 
            cursor: pointer; 
            margin-top: 10px;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-update:hover {
            background-color: #e55a00;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            nav a {
                display: inline-block;
                margin-right: 10px;
                font-size: 13px;
            }
            input[type="text"],
            input[type="tel"] {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <nav>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="catalog.php">Products</a>
        <a href="profile.php">Profile</a>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
            <a href="admin_products.php">Manage Products</a>
        <?php endif; ?>
        <a href="logout.php" onclick="return confirmLogout()">Logout</a>
    </nav>

    <div class="container">
        <h2>My Profile</h2>
        <hr>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-info">
            <p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($user['phone'] ?: 'Not set'); ?></p>
        </div>

        <div class="update-form">
            <h2>Update Profile</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
                <button type="submit" class="btn-update">Update Profile</button>
            </form>
        </div>
    </div>

    <script>
        // Logout confirmation
        function confirmLogout() {
            return confirm('Are you sure you want to logout?');
        }
    </script>

</body>
</html>