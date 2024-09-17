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

// Process form data and insert into the database.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["submitTable"])) {
        // The code inside this block will execute when the "Submit Table" button is clicked.

        // Fetch the total sum from the form data.
        $total_from_form = isset($_POST["totalSum"]) ? $_POST["totalSum"] : 0;

        // Validate and sanitize the input to avoid SQL injection
        $user = mysqli_real_escape_string($conn, $_POST["user"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $total_from_form = floatval($total_from_form);

        // Insert the total sum into the "income" table
        $sql_insert = "INSERT INTO income (user, email, total) VALUES ('$user', '$email', '$total_from_form')";
        if (mysqli_query($conn, $sql_insert)) {
            // Insertion successful
            $response = array("status" => "success");
            echo json_encode($response);
            exit; // Add this line to prevent additional unwanted output
        } else {
            // Insertion failed
            $error_message = "Error: " . mysqli_error($conn);
            echo json_encode(array("status" => "error", "message" => $error_message));
            exit; // Add this line to prevent additional unwanted output
        }
    } else {
        // The code inside this block will execute when the "ADD" button is clicked.

        // Validate and sanitize the input to avoid SQL injection
        $user = mysqli_real_escape_string($conn, $_POST["user"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $pid = mysqli_real_escape_string($conn, $_POST["pid"]);
        $quantity = intval($_POST["quantity"]);

        // Fetch the amount from the "pk_table" based on the provided "pid."
        $name = null;
        $cost = 0;
        $available_quantity = 0; // Initialize available quantity to zero
        $sql = "SELECT cost, name, quantity FROM pk WHERE pid = '$pid'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $name = $row["name"];
            $cost = $row["cost"];
            $available_quantity = intval($row["quantity"]);

            // Check if the quantity in the "pk" table is greater than or equal to the quantity entered in the form.
            if ($available_quantity >= $quantity) {
                // Calculate the total amount based on quantity and amount from the database.
                $calculated_total = $cost * $quantity;

                // Deduct the quantity from the "pk" table
                $new_quantity = $available_quantity - $quantity;
                $sql_update = "UPDATE pk SET quantity = $new_quantity WHERE pid = '$pid'";
                mysqli_query($conn, $sql_update);

                // Send the response as JSON
                $response = array(
                    "status" => "success",
                    "pid" => $pid,
                    "name" => $name,
                    "quantity" => $quantity,
                    "total1" => $calculated_total
                );

                header("Content-Type: application/json");
                echo json_encode($response);
                exit; // Add this line to prevent additional unwanted output
            } else {
                // Insufficient quantity, set a flag in the response.
                $response = array("status" => "insufficient_quantity", "pid" => $pid, "available_quantity" => $available_quantity);
                echo json_encode($response);
                exit; // Stop further processing
            }
        } else {
            // Quantity in "pk" table is not available, show an error message or prevent further processing.
            $response = array("status" => "error", "message" => "Product ID not found in the inventory: $pid");
            echo json_encode($response);
            exit; // Stop further processing
        }
    }
}
?>