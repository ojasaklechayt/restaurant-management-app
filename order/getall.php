<!DOCTYPE html>
<html>
<head>
    <title>All Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        a {
            display: block;
            text-align: center;
            margin-bottom: 20px;
            color: #0066cc;
            text-decoration: none;
        }

        a:hover {
            color: #004d99;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/restraunt/index.php">Main Menu</a>
        <h1>All Orders</h1>
        <?php
            // Database connection
            require '../db.php';

            // Fetch all orders
            $sql = "SELECT orders.*, users.username, menu.item, menu.price
                    FROM orders
                    INNER JOIN users ON orders.user_id = users.id
                    INNER JOIN menu ON orders.item_id = menu.id";
            $result = $conn->query($sql);

            // Check if there are any orders
            if ($result->num_rows > 0) {
                // Output data of each row
                echo "<table>";
                echo "<tr><th>ID</th><th>User</th><th>Item</th><th>Quantity</th><th>Price</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["username"] . "</td>";
                    echo "<td>" . $row["item"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>$" . $row["price"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No orders found</p>";
            }

            // Close database connection
            $conn->close();
        ?>
    </div>
</body>
</html>
