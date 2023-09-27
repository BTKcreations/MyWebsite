<?php
// Database configuration
$db_host = "your_database_host";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hash the password (use a strong hashing algorithm)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database to check if the user exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, verify the password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Password is correct, user is authenticated
            echo "Login successful!";
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    // Close the database connection
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
