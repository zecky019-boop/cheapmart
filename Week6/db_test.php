<?php
// Include database connection
require_once 'includes/config.php';

// Check if connection exists
if ($conn) {
    echo "✅ <strong>DATABASE CONNECTION SUCCESSFUL</strong><br><br>";
    
    // Display connection details
    echo "📊 <strong>Connection Details:</strong><br>";
    echo "Server: " . mysqli_get_server_info($conn) . "<br>";
    echo "Database: cheapmart<br>";
    echo "Host: localhost<br><br>";
    
    // Test query - check if users table exists
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
    if (mysqli_num_rows($result) > 0) {
        echo "✅ 'users' table exists<br>";
    } else {
        echo "⚠️ 'users' table does not exist<br>";
    }
    
    // Test query - count products
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "📦 Total products in database: " . $row['count'] . "<br><br>";
    }
    
    // List all tables
    echo "📋 <strong>Tables in cheapmart database:</strong><br>";
    $result = mysqli_query($conn, "SHOW TABLES");
    while ($row = mysqli_fetch_array($result)) {
        echo "- " . $row[0] . "<br>";
    }
    
    echo "<br><hr>";
    echo "<small>Week 6 - Database Integration Test | " . date('Y-m-d H:i:s') . "</small>";
    
} else {
    echo "❌ <strong>DATABASE CONNECTION FAILED</strong><br>";
    echo "Please check your database settings.";
}
?>