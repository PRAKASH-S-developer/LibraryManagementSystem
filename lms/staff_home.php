<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["name"]) || !isset($_SESSION["pfno"]) || !isset($_SESSION["email"])) {
    header("location:staff_login.php");
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
    <title>Staff Dashboard</title>
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
    <!-- Navbar Section -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">GACC Library Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="staff_home.php">Dashboard</a>
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

    <!-- Main Content Section -->
    <div class="container my-4">
    <h1 class="text-center mb-4">Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h1>

        <!-- Row 1: Book Management Options -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">View All Books</h5>
                        <p class="card-text">View the complete list of books in the library.</p>
                        <a href="staff_view_all_books.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Borrow Book</h5>
                        <p class="card-text">Issue books to staff.</p>
                        <a href="staff_borrow_book.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Return Or Renew Book</h5>
                        <p class="card-text">Manage book return Or Renew by staff.</p>
                        <a href="staff_return_book.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Personal Book Records -->
        <div class="row g-4 mt-3">
            <div class="col-md-6">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">My Borrowed Books</h5>
                        <p class="card-text">View the books you have borrowed.</p>
                        <a href="staff_borrowed_books.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">My Returned Books</h5>
                        <p class="card-text">Check the history of your returned books.</p>
                        <a href="staff_returned_books.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Government Arts College, C. Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
