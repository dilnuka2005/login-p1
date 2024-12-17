<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($new_password != $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $email);

        if ($update_stmt->execute()) {
            // Password changed successfully, show message and redirect to login page
            echo "<script>alert('Password changed successfully!'); window.location.href = 'login.html';</script>";
        } else {
            echo "<script>alert('Failed to change password. Please try again.'); window.location.href = 'forgot-password.html';</script>";
        }
    } else {
        echo "<script>alert('Email not found!'); window.location.href = 'forgot-password.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
