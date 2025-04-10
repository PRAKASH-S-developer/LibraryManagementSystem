<?php
session_start();

include 'send_reminder.php';
include 'auto_run.php';
include 'auto_update_students_years.php';
include 'send_reminder_staffs.php';
include 'auto_run_staffs.php';

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
        body{
            background-color: aquamarine;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" >GACC Library Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="librarian_home.php">Dashboard</a>
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
    <h1 class="text-center mb-4">Librarian Dashboard</h1>

    <div class="row g-4">
        <!-- Row 1 -->
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Add Book</h5>
                    <p class="card-text">Add new books to the library database.</p>
                    <a href="add_book.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Students Borrow Book Request</h5>
                    <p class="card-text">Issue books to students.</p>
                    <a href="librarian_book_issue.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Students Borrowed Book Details</h5>
                    <p class="card-text">Track the history of borrowed books.</p>
                    <a href="borrowed_book_details.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-3">
        <!-- Row 2 -->
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">View All Books</h5>
                    <p class="card-text">View the complete list of books in the library.</p>
                    <a href="view_all_books.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Students Return Book Request</h5>
                    <p class="card-text">Acquire books from students.</p>
                    <a href="librarian_book_return.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Students Returned Books</h5>
                    <p class="card-text">Track the history of returned books.</p>
                    <a href="returned_book_details.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-3">
        <!-- Row 3 -->
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Staffs Borrow Book Request</h5>
                    <p class="card-text">Issue books to staff members.</p>
                    <a href="librarian_book_issue_staffs.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Staffs Borrowed Books</h5>
                    <p class="card-text">Track the history of borrowed books.</p>
                    <a href="staffs_borrowed_books_details.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Staffs Return Book Request</h5>
                    <p class="card-text">Acquire books from staff members.</p>
                    <a href="librarian_book_return_staff.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-3">
        <!-- Row 4 -->
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Staffs Returned Books</h5>
                    <p class="card-text">Track the history of returned books.</p>
                    <a href="staff_returned_book_details.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Add & View All Staff</h5>
                    <p class="card-text">Add & View staff members in the system.</p>
                    <a href="lib_add_staff_home.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Add & View All Students</h5>
                    <p class="card-text">Add & View students in the system.</p>
                    <a href="lib_add_students_home.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-3">
        <!-- Row 5 -->
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Add & View All Cupboard</h5>
                    <p class="card-text">Add & View all cupboards in the system.</p>
                    <a href="lib_add_cupboards_home.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Add & View All Shelves</h5>
                    <p class="card-text">Add & View all shelves in the system.</p>
                    <a href="lib_add_shelves_home.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Add & View Book Keywords</h5>
                    <p class="card-text">Add or View book keywords in the system.</p>
                    <a href="lib_add_book_keyword_home.php" class="btn btn-primary">Go</a>
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