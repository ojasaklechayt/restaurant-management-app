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
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        input[type="text"] {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: #ff0000;
            text-align: center;
            margin-top: 10px;
        }

        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .analytics {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .analytics h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .analytics p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Orders</h1>
        <div class="search-form">
            <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input type="text" name="search" placeholder="Search by username">
                <input type="submit" value="Search">
            </form>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php
            // Database connection
            require '../db.php';

            // Default SQL query to fetch all orders
            $sql = "SELECT orders.*, users.username, menu.item, menu.price
                    FROM orders
                    INNER JOIN users ON orders.user_id = users.id
                    INNER JOIN menu ON orders.item_id = menu.id";

            // If search query is provided
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                // Append search condition to SQL query
                $sql .= " WHERE users.username LIKE '%$search%'";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Initialize variables for analytics
                $totalSales = 0;
                $totalOrders = $result->num_rows;
                $itemQuantities = array();

                while($row = $result->fetch_assoc()) {
                    // Calculate total sales
                    $totalSales += $row["price"];

                    // Calculate item quantities
                    $item = $row["item"];
                    if (!isset($itemQuantities[$item])) {
                        $itemQuantities[$item] = 0;
                    }
                    $itemQuantities[$item] += $row["quantity"];

                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["username"] . "</td>";
                    echo "<td>" . $row["item"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>$" . $row["price"] . "</td>";
                    echo "</tr>";
                }

                // Find the most ordered item directly from SQL
                $sql_most_ordered_item = "SELECT item FROM menu WHERE id = (SELECT item_id FROM orders GROUP BY item_id ORDER BY SUM(quantity) DESC LIMIT 1)";
                $result_most_ordered_item = $conn->query($sql_most_ordered_item);
                $row_most_ordered_item = $result_most_ordered_item->fetch_assoc();
                $mostOrderedItem = $row_most_ordered_item['item'];

                // Calculate average order value
                $averageOrderValue = $totalSales / $totalOrders;

                // Display analytics
                echo "<tr><td colspan='5' class='analytics'>";
                echo "<h2>Analytics</h2>";
                echo "<p><strong>Total Sales:</strong> $" . number_format($totalSales, 2) . "</p>";
                echo "<p><strong>Total Orders:</strong> " . $totalOrders . "</p>";
                echo "<p><strong>Average Order Value:</strong> $" . number_format($averageOrderValue, 2) . "</p>";
                echo "<p><strong>Most Ordered Item:</strong> " . $mostOrderedItem . "</p>";
                echo "</td></tr>";

            } else {
                echo "<tr><td colspan='5'>No orders found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
