<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION["username"]) || !isset($_SESSION["rollno"]) || !isset($_SESSION["email"])) {
    header("location:student_login.php");
    exit();
}

$username = $_SESSION["username"];
$rollno = $_SESSION["rollno"];
$email = $_SESSION["email"];

// Handle logout request
if (isset($_POST["logout"])) {
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session
    header("location:index.html");
    exit();
}

// Retrieve book and student information
if (isset($_GET['bid']) && isset($_GET['stu_id'])) {
    $bid = mysqli_real_escape_string($conn, $_GET['bid']);
    $stu_id = mysqli_real_escape_string($conn, $_GET['stu_id']);

    // Fetch book details
    $book_query = "SELECT * FROM issued_books WHERE bid='$bid' AND stu_id='$stu_id'";
    $book_result = mysqli_query($conn, $book_query);

    if (!$book_result || mysqli_num_rows($book_result) == 0) {
        echo "<script>alert('Book or student not found.'); window.location.href='student_return_book.php';</script>";
        exit();
    }

    $book_row = mysqli_fetch_assoc($book_result);

    $username = $book_row["username"];
    $rollno = $book_row["rollno"];
    $email = $book_row["email"];
    $selectyourdegree = $book_row["selectyourdegree"];
    $selectyourcourse = $book_row["selectyourcourse"];
    $currectstudyingyear = $book_row["currectstudyingyear"];
    $accession_number = $book_row["accession_number"];
    $title = $book_row["title"];
    $book_keyword = $book_row["book_keyword"];
    $author = $book_row["author"];
    $book_image = $book_row["book_image"];
    $copies = $book_row["copies"];
    $publication = $book_row["publication"];
    $publisher = $book_row["publisher"];
    $isbn = $book_row["isbn"];
    $availability = $book_row["availability"];
    $price = $book_row["price"];
    $cupboard_name = $book_row["cupboard_name"];
    $shelve_name = $book_row["shelve_name"];
    $issued_date = $book_row["issued_date"];
    $exp_return_date = $book_row["return_date"];

    // Calculate renewal date by adding 15 days to the expected return date
    $renew_date = date('Y-m-d'); // Current date
    $new_return_date = date('Y-m-d', strtotime($exp_return_date . ' +15 days')); // New return date

    // Insert into int_student_renewal_books table
    $issue_query = "INSERT INTO int_student_renewal_books (in_renewal_id, stu_id, username, rollno, 
    email, selectyourdegree, selectyourcourse, currectstudyingyear, bid, accession_number, title, 
    book_keyword, author, book_image, copies, publication, publisher, isbn, availability, price, 
    cupboard_name, shelve_name, issued_date, exp_return_date, renew_date, new_return_date) 
    VALUES (NULL, '$stu_id', '$username', '$rollno', '$email', '$selectyourdegree', '$selectyourcourse',
    '$currectstudyingyear', '$bid', '$accession_number', '$title', '$book_keyword', '$author', '$book_image',
     '$copies', '$publication', '$publisher', '$isbn', '$availability', '$price', '$cupboard_name', '$shelve_name',
      '$issued_date', '$exp_return_date', '$renew_date', '$new_return_date')";

    if (mysqli_query($conn, $issue_query)) {
        echo "<script>alert('Renewal Request sent successfully! Check your Email for further updates.'); window.location.href='student_home.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error Renewing book: " . mysqli_error($conn) . "'); window.location.href='student_home.php';</script>";
    }
} else {
    header("Location: students_home.php");
    exit();
}
?>