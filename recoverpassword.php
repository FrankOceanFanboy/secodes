<?php

// Function to generate a random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Placeholder for database connection (replace with your actual database connection code)
$dbConnection = mysqli_connect("your_host", "your_username", "your_password", "your_database");

if (!$dbConnection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user's email from the form
    $email = mysqli_real_escape_string($dbConnection, $_POST["email"]);

    // Check if the email exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($dbConnection, $query);

    if (mysqli_num_rows($result) > 0) {
        // Generate a unique token
        $token = generateToken();

        // Store the token and user email in the database (you may want to store it with an expiration time)
        $updateQuery = "UPDATE users SET reset_token = '$token' WHERE email = '$email'";
        mysqli_query($dbConnection, $updateQuery);

        // Send an email to the user with a link containing the token
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        $emailSubject = "Password Reset";
        $emailBody = "Click the following link to reset your password: $resetLink";
        // Use your preferred method to send the email (e.g., mail(), PHPMailer, etc.)
        mail($email, $emailSubject, $emailBody);

        echo "An email has been sent with instructions to reset your password.";
    } else {
        echo "Email not found in the database.";
    }
}

mysqli_close($dbConnection);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
</head>
<body>

    <h2>Password Recovery</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <input type="submit" value="Reset Password">
    </form>

</body>
</html>
