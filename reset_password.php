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
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($new_password != $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Verify OTP
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND otp = ?");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password and clear OTP
        $update_stmt = $conn->prepare("UPDATE users SET password = ?, otp = NULL WHERE email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $email);

        if ($update_stmt->execute()) {
            echo "Password reset successfully!";
        } else {
            echo "Failed to reset password. Please try again.";
        }
    } else {
        echo "Invalid OTP or email.";
    }

    $stmt->close();
    $conn->close();
}
?>
