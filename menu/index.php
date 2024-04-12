<!DOCTYPE html>
<html>
<head>
    <title>View Menu</title>
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

        .menu-item {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .menu-item:hover {
            background-color: #f2f2f2;
        }

        .item-info {
            font-weight: bold;
            color: #333;
        }

        .item-price {
            color: #0066cc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Menu</h1>
        <?php
        // Database connection
        require '../db.php';

        $sql = "SELECT * FROM menu";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="menu-item">';
                echo '<span class="item-info">Item: </span>' . $row["item"]. '<br>';
                echo '<span class="item-info">Price: </span><span class="item-price">$' . $row["price"]. '</span>';
                echo '</div>';
            }
        } else {
            echo "<p>No menu items found</p>";
        }

        // Close database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
