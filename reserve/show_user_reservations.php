<!DOCTYPE html>
<html>
<head>
    <title>All Reservations</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #0066cc;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Reservations</h1>
        <table>
            <tr>
                <th>Username</th>
                <th>Table Number</th>
                <th>Reservation Time</th>
            </tr>
            <?php
                // Start the session
                session_start();

                // Database connection
                require '../db.php';

                $userName = $_SESSION['username'];
                $userIdQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $userIdQuery->bind_param("s", $userName);
                $userIdQuery->execute();
                $userResult = $userIdQuery->get_result();
                $userID = $userResult->fetch_assoc()['id'];
                
                // Query to get all reservations
                $sql = "SELECT users.username, reservations.table_number, reservations.reservation_time FROM reservations INNER JOIN users ON reservations.user_id=users.id where reservations.user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userID);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["table_number"] . "</td>";
                        echo "<td>" . $row["reservation_time"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No reservations found</td></tr>";
                }

                // Close database connection
                $conn->close();
            ?>
        </table>
    </div>
</body>
</html>