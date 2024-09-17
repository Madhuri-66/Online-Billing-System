<?php
// Function to establish a database connection using PDO
function connectToDatabase()
{
    $host = 'localhost';
    $dbname = 'bill';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Function to fetch stock details from the "pk" table in ascending order of quantity
function getStockDetails()
{
    try {
        $pdo = connectToDatabase();
        $stmt = $pdo->query("SELECT * FROM pk ORDER BY quantity ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching stock details: " . $e->getMessage());
    }
}

// Function to update the quantity in the "pk" table
function addToQuantity($id, $quantityToAdd)
{
    try {
        $pdo = connectToDatabase();
        $stmt = $pdo->prepare("UPDATE pk SET quantity = quantity + :quantityToAdd WHERE pid = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':quantityToAdd', $quantityToAdd, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error adding quantity: " . $e->getMessage());
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['pid'];
    $quantityToAdd = intval($_POST['quantity']);

    if ($quantityToAdd > 0) {
        addToQuantity($id, $quantityToAdd);
    }
}

// Fetch stock details from the "pk" table
$stockDetails = getStockDetails();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stock Details</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #e8d2d5;
    }

    .container {
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ccc;
        background-color: #FFFFFF;
    }

    h1 {
        text-align: center;
    }

    form {
        display: flex;
        align-items: center;
    }

    label {
        margin-top: 10px;
    }

    /* Added styles for the table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Adjusted styles for the input and button */
    input[type="number"] {
        width: 60px;
        padding: 5px;
        text-align: center;
    }

    /* Set the form elements to display inline-flex */
    form input,
    form button {
        display: inline-flex;
        margin: 5px;
    }

</style>
<body>
    <div class="container">
        <h1>Stock Details</h1>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Stock Name</th>
                <th>Quantity</th>
                <th>Quantity to Add</th>
            </tr>
            <?php foreach ($stockDetails as $stock): ?>
                <tr>
                    <td><?= $stock['pid']; ?></td>
                    <td><?= $stock['name']; ?></td>
                    <td><?= $stock['quantity']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="pid" value="<?= $stock['pid']; ?>">
                            <input type="number" name="quantity" min="1" required>
                            <button type="submit">Add</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
