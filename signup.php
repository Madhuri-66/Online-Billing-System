<?php
// Replace these values with your actual MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bill";

// Variable to track successful sign-up
$signUpSuccess = false;

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username, password, and email
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if username or email already exists in the database
    $checkQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Username or email already exists. Please choose a different one.'); window.location.href = 'signup.html';</script>";
    } else {
        // Prepare a SQL statement to insert the user details into the database
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            $signUpSuccess = true;
            // Redirect to the login page after successful sign-up
            header("Location: login.html");
            exit; // Ensure script execution stops after the redirect
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}
?>