<?php

// Placeholder for database connection (replace with your actual database connection code)
$dbConnection = mysqli_connect("your_host", "your_username", "your_password", "your_database");

if (!$dbConnection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the token is provided in the URL
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($dbConnection, $_GET['token']);

    // Check if the token exists in the database
    $query = "SELECT * FROM users WHERE reset_token = '$token' AND reset_token_expires > NOW()";
    $result = mysqli_query($dbConnection, $query);

    if (mysqli_num_rows($result) > 0) {
        // Token is valid, allow the user to reset the password
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the new password from the form
            $newPassword = mysqli_real_escape_string($dbConnection, $_POST["new_password"]);

            // Update the password in the database and clear the token
            $row = mysqli_fetch_assoc($result);
            $email = $row['email'];
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateQuery = "UPDATE users SET password = '$hashedPassword', reset_token = NULL, reset_token_expires = NULL WHERE email = '$email'";
            mysqli_query($dbConnection, $updateQuery);

            echo "Password reset successfully. You can now <a href='login.php'>login</a> with your new password.";
        }
    } else {
        echo "Invalid or expired token. Please try again or request a new password reset.";
    }
} else {
    echo "Token not provided in the URL.";
}

mysqli_close($dbConnection);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>

    <h2>Password Reset</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>
        <br>
        <input type="submit" value="Reset Password">
    </form>

</body>
</html>
