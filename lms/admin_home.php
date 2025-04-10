<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION["name"]) || !isset($_SESSION["pfno"]) || !isset($_SESSION["email"])) {
    header("location:admin_login.php");
    exit();
}

$name = $_SESSION["name"];
$pfno = $_SESSION["pfno"];
$email = $_SESSION["email"];

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
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        footer {
            background-color: black;
            color: yellow;
            text-align: center;
            padding: 10px 0;
            margin-top: 130px;
        }
        body{
            background-color: aqua;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Library Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_home.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <!-- <a class="nav-link" href="#">Profile</a> -->
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

    <div class="container my-4">
        <h1 class="text-center mb-4">Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
        <div class="row g-4">
            <!-- View All Books -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Students</h5>
                        <p class="card-text">Add new students to the system.</p>
                        <a href="add_students_home.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- Borrow Book -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Staff</h5>
                        <p class="card-text">Add Staff to the system.</p>
                        <a href="admin_add_staffs_home.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- Return Book -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Cupboard</h5>
                        <p class="card-text">Add Cupboard to the system.</p>
                        <a href="add_cupboard_home.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>







            <!-- View All Books -->
            <!-- <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Shelves</h5>
                        <p class="card-text">Add new Shelvesto the system.</p>
                        <a href="add_shelves.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div> -->

            <!-- Borrow Book -->
            <!-- <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Keyword</h5>
                        <p class="card-text">Add Book Keyword to the system.</p>
                        <a href="add_book_keyword_home.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div> -->

            <!-- Return Book -->
            <!-- <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Cupboard</h5>
                        <p class="card-text">Add Cupboard to the system.</p>
                        <a href="add_cupboard_home.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div> -->









            <!-- View My Borrowed Books -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Shelves</h5>
                        <p class="card-text">Add new Shelvesto the system.</p>
                        <a href="add_shelves.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- Add Librarian -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Librarian</h5>
                        <p class="card-text">Add new Librarian to the system.</p>
                        <a href="librarian_acc_create_home.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- View My Returned Books -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Add Book Keyword</h5>
                        <p class="card-text">Add Book Keyword to the system.</p>
                        <a href="add_book_keyword_home.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc.Computer Science</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>