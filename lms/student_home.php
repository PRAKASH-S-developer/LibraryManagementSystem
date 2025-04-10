<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");
// Check if the user is logged in
if (!isset($_SESSION["username"]) || !isset($_SESSION["rollno"]) || !isset($_SESSION["email"])) {
    header("location:student_login.php");
    exit();
}
$username = $_SESSION["username"];
$rollno = $_SESSION["rollno"];
$email = $_SESSION["email"];

// if (isset($_POST["go"])) {
    // Check if roll number exists in temp_borrow_book_request table
    // $checkQuery = "SELECT * FROM temp_borrow_book_request WHERE rollno='$rollno'";
    // $checkResult = mysqli_query($conn, $checkQuery);

    // $checkQuery1 = "SELECT * FROM temp_return_book_request WHERE rollno='$rollno'";
    // $checkResult1 = mysqli_query($conn, $checkQuery1);

    // $checkQuery2 = "SELECT * FROM returned_book WHERE rollno='$rollno'";
    // $checkResult2 = mysqli_query($conn, $checkQuery2);

    // if (mysqli_num_rows($checkResult) > 0) {
    //     echo "<script>alert('You have already requested a book.');</script>";
    // }if(mysqli_num_rows($checkResult1) > 0){
    //     echo "<script>alert('Your return request is waiting for approval.');</script>";
    // }

    // }if(mysqli_num_rows($checkResult2) > 0){
    //     header("location:student_borrow_book.php");
    //     exit();
    // }
//}


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
    <title>Student Dashboard</title>
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
                        <a class="nav-link active" href="student_home.php">Dashboard</a>
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
        <h1 class="text-center mb-4">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        <div class="row g-4">
            <!-- View All Books -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">View All Books</h5>
                        <p class="card-text">Browse the entire library collection.</p>
                        <a href="student_view_all_books.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- Borrow Book -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Borrow Book</h5>
                        <p class="card-text">Request to borrow books from the library.</p>
                        <a href="student_borrow_book.php" class="btn btn-primary">Go</a>
                        <!-- <form method="POST" action="">
                            <button type="submit" name="go" class="btn btn-primary">Go</button>
                        </form> -->
                    </div>
                </div>
            </div>

            <!-- Return Book -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Return Or Renew Book</h5>
                        <p class="card-text">Manage your book returns.</p>
                        <a href="student_return_book.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- View My Borrowed Books -->
            <div class="col-md-6">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">My Borrowed Books</h5>
                        <p class="card-text">View the books you have borrowed.</p>
                        <a href="student_borrowed_book.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- View My Returned Books -->
            <div class="col-md-6">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">My Returned Books</h5>
                        <p class="card-text">Check the history of your returned books.</p>
                        <a href="student_returned_book.php" class="btn btn-primary">Go</a>
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
