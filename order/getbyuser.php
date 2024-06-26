<!DOCTYPE html>
<html>

<head>
    <title>My Orders</title>
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

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0px;
            overflow: hidden;
            background-color: lightgray;
        }

        li {
            float: left;
        }

        li a {
            display: block;
            color: blue;
            font-size: 20px;
            text-align: center;
            padding: 10px 20px;
            text-decoration: none;
        }

        .active {
            background-color: gray;
            color: white;
        }

        li a:hover {
            background-color: orange;
            color: white;
        }
    </style>
</head>

<body>
    <nav>
        <ul>
            <?php
            // Start the session
            session_start();

            // Check if user is logged in
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                // If logged in, show logout link
                echo '<li><a href="/restraunt/index.php">Main Menu</a></li>';
                echo '<li><a href=""> Logged in as ' . $_SESSION['username'] . '</a></li>';
                echo '<li><a href="/restraunt/logout/index.php">Logout</a></li>';
            } else {
                // If not logged in, show login link
                echo '<li><a href="/restraunt/register/index.php">Register</a></li>';
                echo '<li><a href="/restraunt/login/index.php">Login</a></li>';
            }
            ?>
        </ul>
    </nav>
    <div class="container">
        <h1>My Orders</h1>
        <table>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php
            // Database connection
            require '../db.php';

            // Get user ID based on session or user input
            $userName = $_SESSION['username'];
            $userIdQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $userIdQuery->bind_param("s", $userName);
            $userIdQuery->execute();
            $userResult = $userIdQuery->get_result();
            $userID = $userResult->fetch_assoc()['id'];
            // SQL query to fetch user's orders
            $sql = "SELECT orders.*, menu.item, menu.price
                    FROM orders
                    INNER JOIN menu ON orders.item_id = menu.id
                    WHERE orders.user_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["item"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>$" . $row["price"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No orders found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>