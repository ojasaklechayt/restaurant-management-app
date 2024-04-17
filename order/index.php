<!DOCTYPE html>
<html>

<head>
    <title>Place an Order</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        form {
            text-align: center;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #0066cc;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #004d99;
        }

        .order-success {
            margin-top: 20px;
            text-align: center;
            color: green;
            font-weight: bold;
        }

        .main-menu-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #0066cc;
            text-decoration: none;
        }

        .main-menu-link:hover {
            color: #004d99;
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
        <h1>Place an Order</h1>
        <?php
        // Database connection
        require '../db.php';

        // When form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = mysqli_real_escape_string($conn, $_POST["username"]);
            $item_id = mysqli_real_escape_string($conn, $_POST["item_id"]);
            $quantity = mysqli_real_escape_string($conn, $_POST["quantity"]);

            // Get user_id from username
            $sql = "SELECT id FROM users WHERE username='$username'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $user_id = $row["id"];

            // Get the price and name of the selected item
            $sql = "SELECT item, price FROM menu WHERE id='$item_id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $price = $row["price"];
            $item_name = $row["item"];

            // Calculate the total price
            $total_price = $price * $quantity;

            $sql = "INSERT INTO orders (user_id, item_id, quantity)
                VALUES ('$user_id', '$item_id', '$quantity')";

            if ($conn->query($sql) === TRUE) {
                echo '<p class="order-success">Order placed successfully. Total Price: $' . $total_price . '. Item: ' . $item_name . '. Quantity: ' . $quantity . '</p>';
                echo '<a class="main-menu-link" href="/restraunt/index.php">Main Menu</a>';
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Get all items from the menu
        $sql = "SELECT * FROM menu";
        $result = $conn->query($sql);
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            onsubmit="return confirmOrder()">
            Username: <input type="text" name="username"><br>
            Item: <select name="item_id" id="item_id">
                <?php
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '" data-price="' . $row["price"] . '">' . $row["item"] . ' - $' . $row["price"] . '</option>';
                    }
                }
                ?>
            </select><br>
            Quantity: <input type="number" name="quantity" id="quantity"><br>
            <input type="submit" value="Place Order">
        </form>
    </div>

    <script>
        function confirmOrder() {
            var item = document.getElementById("item_id").value;
            var quantity = document.getElementById("quantity").value;
            var itemName = document.getElementById("item_id").options[document.getElementById("item_id").selectedIndex].text;
            var itemPrice = document.getElementById("item_id").options[document.getElementById("item_id").selectedIndex].getAttribute('data-price');
            var totalPrice = quantity * itemPrice;
            return confirm("You have selected " + quantity + " quantity of " + itemName + " at $" + itemPrice + " per item. Total Price: $" + totalPrice + ". Do you want to confirm your order?");
        }
    </script>
</body>

</html>