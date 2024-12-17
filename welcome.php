<?php
// Start a session to track the user
session_start();

// Check if the user is logged in by checking the session (this assumes you've implemented login functionality)
if (!isset($_SESSION['email'])) {
    header("Location: login.html"); // Redirect to the login page if not logged in
    exit();
}

// Get user details (this assumes you've stored them in the session)
$user_email = $_SESSION['email']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="welcome.css"> <!-- Optional: You can style the page -->
</head>
<body>
    <div class="welcome-container">
        <h2>Welcome to the site!</h2>
        <p>Hi, <?php echo htmlspecialchars($user_email); ?>! Thank you for registering.</p>
        <p>Feel free to explore the site.</p>
        <a href="logout.php">Logout</a> <!-- Optional: Add a logout link -->
    </div>
</body>
</html>
