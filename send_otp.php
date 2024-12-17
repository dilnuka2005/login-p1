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

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate OTP
        $otp = mt_rand(100000, 999999);

        // Update OTP in database
        $update_stmt = $conn->prepare("UPDATE users SET otp = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $otp, $email);
        $update_stmt->execute();

        // Send OTP email
        $subject = "Password Reset OTP";
        $message = "Your OTP for password reset is: $otp";
        $headers = "From: no-reply@yourwebsite.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "OTP sent successfully. Please check your email.";
            header("Location: reset_password.html");
        } else {
            echo "Failed to send OTP. Please try again.";
        }
    } else {
        echo "Email not found!";
    }

    $stmt->close();
    $conn->close();
}
?>
