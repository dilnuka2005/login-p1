<?php
// Database connection settings
$servername = "localhost"; // Change if necessary
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "user_database"; // The database name you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the password match
    if ($password != $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert data into users table
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $full_name, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        // Display success message
        echo "<p>Registration successful! Welcome, " . htmlspecialchars($full_name) . ".</p>";
        echo "<p>You will be redirected to the login page shortly...</p>";

        // Redirect to the login page after a short delay (3 seconds)
        header("refresh:3;url=login.html");
        exit(); // Ensure no further script execution
    } else {
        // Display a more detailed error if the insertion fails
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
