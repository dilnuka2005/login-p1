<?php
// Database connection settings
$servername = "localhost"; // Change if necessary
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "user_database"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to check if the email exists
    $stmt = $conn->prepare("SELECT full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($full_name, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Login successful
            echo "Welcome, " . htmlspecialchars($full_name) . "!";
            // Redirect to YouTube channel
            header("Location: https://www.youtube.com/channel/UC6dd601bYXS5lMI6HiJjy7Q");
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Invalid email or password.'); window.location.href = 'login.html';</script>";
        }
    } else {
        // Email not found
        echo "<script>alert('Invalid email or password.'); window.location.href = 'login.html';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
