<!DOCTYPE html>
<html>
<head>
    <title>Reserve a Table</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
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
        input[type="datetime-local"] {
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

        .error {
            color: #ff0000;
            text-align: center;
            margin-top: 10px;
        }

        .success {
            color: #008000;
            text-align: center;
            margin-top: 10px;
        }

        .link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #0066cc;
        }

        .link:hover {
            color: #004d99;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reserve a Table</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            Username: <input type="text" name="username"><br>
            Table Number: <input type="text" name="table_number"><br>
            Reservation Time: <input type="datetime-local" name="reservation_time"><br>
            <input type="submit" value="Reserve">
        </form>
        <?php
        // Database connection
        require '../db.php';

        // When form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = mysqli_real_escape_string($conn, $_POST["username"]);
            $table_number = mysqli_real_escape_string($conn, $_POST["table_number"]);
            $reservation_time = mysqli_real_escape_string($conn, $_POST["reservation_time"]);

            // Get user_id from username
            $sql = "SELECT id FROM users WHERE username='$username'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    $user_id = $row["id"];
                }

                // Check if the table is already reserved for the specific time
                $sql = "SELECT * FROM reservations WHERE table_number='$table_number' AND reservation_time='$reservation_time'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<div class="error">Table is already reserved for the selected time. Please choose a different time.</div>';
                } else {
                    $sql = "INSERT INTO reservations (user_id, table_number, reservation_time)
                    VALUES ('$user_id', '$table_number', '$reservation_time')";

                    if ($conn->query($sql) === TRUE) {
                        echo '<div class="success">Reservation made successfully</div>';
                        echo '<a href="/restraunt/index.php" class="link">Main Menu</a>';
                    } else {
                        echo '<div class="error">Error: ' . $sql . '<br>' . $conn->error . '</div>';
                    }
                }
            } else {
                echo '<div class="error">Username does not exist</div>';
            }
        }
        ?>
    </div>
</body>
</html>
