<?php
// Database connection test for CheapMart
$servername = "localhost";
$username = "root";
$password = "";
$database = "cheapmart";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if ($conn) {
    echo "<h1 style='color: green;'>✅ Database Connection Successful!</h1>";
    echo "<p>Connected to: <strong>" . $database . "</strong></p>";
    echo "<p>Server version: " . mysqli_get_server_info($conn) . "</p>";
    
    // Test query to check users
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
    $row = mysqli_fetch_assoc($result);
    echo "<p>Total users in database: <strong>" . $row['total'] . "</strong></p>";
    
    // Test query to check products
    $result2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
    $row2 = mysqli_fetch_assoc($result2);
    echo "<p>Total products in database: <strong>" . $row2['total'] . "</strong></p>";
    
    echo "<hr>";
    echo "<p style='color: blue;'>Phase 1 Database Setup: <strong>✅ SUCCESS</strong></p>";
} else {
    echo "<h1 style='color: red;'>❌ Connection Failed</h1>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
}
?>