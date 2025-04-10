<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");
// Check if the user is logged in
if (!isset($_SESSION["name"]) || !isset($_SESSION["pfno"]) || !isset($_SESSION["email"])) {
    header("location:librarian_login.php");
    exit();
}

// Handle logout request
if (isset($_POST["logout"])) {
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session
    header("location:index.html");
    exit();
}

// Fetch shelves from the database
$query = "SELECT * FROM shelves";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Shelves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
            body {
            background-color: aquamarine;
        }

        footer {
            background-color: black;
            color: yellow;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }
        li a{
            text-decoration: none;
            color: white;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand">GACC Library Management System</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <!-- <a class="nav-link" href="librarian_home.php">Home</a>  -->
                    </li>
                    <li class="nav-item">
                    <!-- <a class="nav-link" href="add_shelves.php">Back</a> -->
                    <form method="POST" action="">
                            <button  class="btn btn-danger btn-sm"><a href="lib_add_shelves_home.php">Back</a></button>
                        </form>
                    </li> &nbsp;&nbsp;&nbsp;&nbsp;
                    <li class="nav-item">
                        <form method="POST" action="">
                            <button type="submit" name="logout" class="btn btn-danger btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h1 class="text-center mb-4">Available Shelves</h1>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Shelve ID</th>
                    <th scope="col">Shelve Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['shelve_id'] . "</td>";
                        echo "<td>" . $row['shelve_name'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2' class='text-center'>No shelves available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br>

    <footer class="text-center mt-4 py-3 bg-dark">
    2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
