<!DOCTYPE html>
<html>

<head>
    <title>Restaurant Reservation System</title>
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

        .btn {
            display: block;
            padding: 15px 20px;
            margin-bottom: 10px;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }

        .btn:hover {
            filter: brightness(90%);
        }

        .btn-primary {
            background-color: #0066cc;
        }

        .btn-primary:hover {
            background-color: #004d99;
        }

        .btn-success {
            background-color: #4CAF50;
        }

        .btn-success:hover {
            background-color: #45a049;
        }

        .btn-warning {
            background-color: #ff9800;
        }

        .btn-warning:hover {
            background-color: #e68a00;
        }

        .btn-danger {
            /* Red color for logout button */
            background-color: #ff0000;
        }

        .btn-danger:hover {
            background-color: #cc0000;
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
        <h1>Welcome to our Restaurant Reservation System!</h1>
        <?php

        // Check if user is logged in
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            // Check if user is admin
            if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
                echo '<a href="/restraunt/reserve/show_reservations.php" class="btn btn-primary">Display all the reserved tables</a>';
                echo '<a href="/restraunt/menu/index.php" class="btn btn-primary">View Menu</a>';
                echo '<a href="/restraunt/menu/add.php" class="btn btn-primary">Add into Menu</a>';
                echo '<a href="/restraunt/order/getall.php" class="btn btn-primary">Show all Orders</a>';
            } else {
                echo '<a href="/restraunt/reserve/index.php" class="btn btn-success">Reserve a Table</a>';
                echo '<a href="/restraunt/reserve/show_user_reservations.php" class="btn btn-success">Show my reserved tables</a>';
                echo '<a href="/restraunt/menu/index.php" class="btn btn-success">View Menu</a>';
                echo '<a href="/restraunt/order/index.php" class="btn btn-success">Place an Order</a>';
                echo '<a href="/restraunt/order/getbyuser.php" class="btn btn-success">Show your Orders</a>';
            }
        } else {
            echo '<a href="/restraunt/register/index.php" class="btn btn-warning">Register</a>';
            echo '<a href="/restraunt/login/index.php" class="btn btn-warning">Login</a>';
        }
        ?>
    </div>
</body>

</html>