<?php
// Database connection credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bill";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the POST request
$pid = $_POST['pid'];
$brand = $_POST['brand'];
$name = $_POST['name'];
$cost = $_POST['cost'];
$quantity = $_POST['quantity'];

// Check if the given pid already exists in the table
$sql_check = "SELECT * FROM pk WHERE pid = '$pid'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    // If the pid exists, fetch the existing details and populate the form fields
    $row = $result_check->fetch_assoc();
    $existing_brand = $row['brand'];
    $existing_name = $row['name'];
    $existing_cost = $row['cost'];

    // Fill the remaining details automatically
    $brand = $existing_brand;
    $name = $existing_name;
    $cost = $existing_cost;

    // Update the quantity for the existing row
    $existing_quantity = $row['quantity'];
    $new_quantity = $existing_quantity + $quantity;

    $sql_update = "UPDATE pk SET quantity = $new_quantity WHERE pid = '$pid'";
    if ($conn->query($sql_update) === TRUE) {
        echo "Product data updated successfully";
        header("Location: pk.html");
    } else {
        echo "Error updating product data: " . $conn->error;
    }
} else {
    // If the pid does not exist, insert a new row into the table
    $sql_insert = "INSERT INTO pk (pid, brand, name, cost, quantity) VALUES ('$pid', '$brand', '$name', '$cost', '$quantity')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "Product data inserted successfully";
        header("Location: pk.html");
    } else {
        echo "Error inserting product data: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>