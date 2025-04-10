<?php

session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");
// Redirect to login page if the user is not logged in
if (!isset($_SESSION["name"]) || !isset($_SESSION["pfno"]) || !isset($_SESSION["email"])) {
    header("location:admin_login.php");
    exit();
}

$name = $_SESSION["name"];
$pfno = $_SESSION["pfno"];
$email = $_SESSION["email"];

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("location:admin_login.php");
    exit();
}

// Fetch shelves from the database
$query = "SELECT * FROM lib_acc_create";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Shelves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body { 
            background-color: rgb(84, 109, 135); 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
        }
        .navbar { 
            background-color: #343a40; 
        }
        .navbar-brand, .navbar a { 
            color: #ffffff !important; 
        }
        .container { 
            flex: 1; 
        }
        table { 
            background-color: #fff; 
            border-radius: 8px; 
        }
        th, td { 
            text-align: center; 
            vertical-align: middle; 
        }
        footer { 
            background-color: black; 
            color: yellow; 
            text-align: center; 
            padding: 10px 0; 
            margin-top: auto; 
        }
        .search-bar {
            width: 100%;
            max-width: 400px;
            margin: 0 auto 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
            font-size: 16px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        }
        .btn-danger a {
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
                        <form method="POST" action="">
                            <button class="btn btn-danger btn-sm"><a href="librarian_acc_create_home.php">Back</a></button>
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
        <h1 class="text-center mb-4">View Librarian</h1>
        <input type="text" id="search" class="search-bar form-control" placeholder="Search by any field...">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Librarian Name</th>
                    <th>PF NO</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody id="bookTable">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['lib_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['pfno']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br>

    <footer class="text-center mt-4 py-3 bg-dark">
        2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#bookTable tr").filter(function() {
                    // Search across all columns
                    var rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>