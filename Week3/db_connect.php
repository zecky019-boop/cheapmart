<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cheapmart";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully to CheapMart database.<br>";
// Optional: Display server info
echo "Server info: " . mysqli_get_server_info($conn);
?>