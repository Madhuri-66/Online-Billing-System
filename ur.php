<!DOCTYPE html>
<html>
<head>
    <title>Sales Table</title>
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

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-top: 10px;
    }

    

    /* Styles for the Submit Table button */
    #submitTable {
        background-color: #001f3f;
        color: #fff;
        padding: 10px;
        cursor: pointer;
    }
</style>

</head>
<body><div class="container">
    <h1>Sales Table</h1>

    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Total</th>
        </tr>
        
        <?php
            // Establish the database connection
            $servername = "localhost";
            $username = "root"; // Replace with your MySQL username
            $password = "";     // Replace with your MySQL password
            $dbname = "bill"; // Replace with the name of your database

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch data from the 'income' table
            $sql = "SELECT user, email, total FROM income";
            $result = $conn->query($sql);

            // Loop through the data and display it in the table
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['user'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['total'] . "</td>";
                echo "</tr>";
            }

            // Close the database connection
            $conn->close();
        ?>
    </table>
</div>
</body>
</html>