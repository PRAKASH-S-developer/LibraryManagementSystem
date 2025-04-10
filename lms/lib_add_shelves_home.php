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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        footer {
            background-color: black;
            color: yellow;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }
        body {
            background-image: url('shelves1.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand">GACC Library Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="librarian_home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <!-- Logout button -->
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="logout" class="btn btn-link nav-link" style="text-decoration: none;">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Dashboard Content -->
    <div class="container my-4">
        <!-- <h1 class="text-center mb-4">Librarian Dashboard</h1>--><br><br><br><br><br><br><br><br> 


        <div class="row g-4 justify-content-center">

        <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">View All Shelves</h5>
                        <p class="card-text">View All shelves details.</p>
                        <a href="lib_view_all_shelves.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- Card 1: Add New Shelves -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add New Shelves</h5>
                        <p class="card-text">Adding new shelves details to the system.</p>
                        <a href="lib_add_new_shelve.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Delete Shelves -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Delete or Update Shelves</h5>
                        <p class="card-text">Delete Or Update shelves.</p>
                        <a href="lib_delete_update_shelves.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- Card 3: Add Cupboards -->
            <!-- <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Cupboards</h5>
                        <p class="card-text">Add new cupboards to the library system.</p>
                        <a href="add_cupboard.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
<br><br><br><br><br><br>
    <footer>
    2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
