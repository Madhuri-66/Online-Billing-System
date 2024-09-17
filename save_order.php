<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "bill";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to save the order to the database
function saveOrderToDatabase($username, $email, $products, $grandTotal) {
  global $conn;

  // Sanitize the input to prevent SQL injection (you should use prepared statements)
  $name = $conn->real_escape_string($name);
  $email = $conn->real_escape_string($email);
  $grandTotal = $conn->real_escape_string($grandTotal);

  // Assuming you have a 'users' table to store user data (you should have appropriate columns)
  $userSql = "INSERT INTO income (username, email,total) VALUES ('$name', '$email','$grandTotal')";
  $conn->query($userSql);

  // Get the user ID of the inserted user
  $userId = $conn->insert_id;

  // Assuming you have an 'order_items' table to store order data (you should have appropriate columns)
  foreach ($products as $product) {
    $productId = $product['productId'];
    $quantity = $product['quantity'];
    $totalCost = $product['totalCost'];

    // Assuming you have appropriate columns in the 'order_items' table to store order data
    $orderItemSql = "INSERT INTO order_items (user_id, product_id, quantity, total_cost) VALUES ('$userId', '$productId', '$quantity', '$totalCost')";
    $conn->query($orderItemSql);
  }

  // Assuming you have a 'user_orders' table to store the user's total order cost
  $userOrderSql = "INSERT INTO income (name, total_order_cost) VALUES ('$userId', '$grandTotal') ON DUPLICATE KEY UPDATE total_order_cost = '$grandTotal'";
  $conn->query($userOrderSql);

  // Return a response indicating success
  $response = array('message' => 'Order submitted successfully.');
  echo json_encode($response);
}

// Retrieve the order data from the request body
$requestData = json_decode(file_get_contents('php://input'), true);

if ($requestData) {
  $username = $requestData['username'];
  $email = $requestData['email'];
  $products = $requestData['products'];
  $grandTotal = $requestData['grandTotal'];

  saveOrderToDatabase($username, $email, $products, $grandTotal);
}
?>
