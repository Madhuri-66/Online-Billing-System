<?php
// Replace these values with your actual MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bill";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare a SQL statement to check the credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    // Execute the query
    $result = $conn->query($sql);

    // Check if a row was returned (successful login)
    if ($result->num_rows == 1) {
        
        // Redirect to the dashboard or another page
         header("Location: home.html");
        // exit;
    } else {
        echo "Invalid credentials. Please try again.";
    }

    // Close the database connection
    $conn->close();
}
?>