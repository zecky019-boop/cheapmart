<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; // In real app, hash it

    // Simple server-side validation
    $errors = [];
    if (empty($fullname)) $errors[] = "Name required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
    if (strlen($password) < 6) $errors[] = "Password too short";

    if (empty($errors)) {
        // Here you would insert into database
        echo "<h2>Registration Successful (simulated)</h2>";
        echo "Name: $fullname<br>Email: $email";
    } else {
        echo "<h2>Errors:</h2><ul>";
        foreach ($errors as $err) echo "<li>$err</li>";
        echo "</ul>";
    }
} else {
    echo "Invalid request method.";
}
?>