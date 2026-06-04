<?php
$conn = mysqli_connect("localhost", "root", "", "cheapmart");
if($conn){
    echo "✅ Connected successfully to CheapMart database.";
} else {
    echo "❌ Connection failed: " . mysqli_connect_error();
}
?>