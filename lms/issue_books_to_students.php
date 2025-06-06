<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION["name"])) {
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

// Retrieve book and student information
if (isset($_GET['bid']) && isset($_GET['stu_id'])) {
    $bid = mysqli_real_escape_string($conn, $_GET['bid']);
    $stu_id = mysqli_real_escape_string($conn, $_GET['stu_id']);

    // Fetch book details
    $book_query = "SELECT * FROM temp_borrow_book_request WHERE bid='$bid' AND stu_id='$stu_id'";
    $book_result = mysqli_query($conn, $book_query);

    if (!$book_result || mysqli_num_rows($book_result) == 0) {
        echo "<script>alert('Book or student not found.'); window.location.href='provide_books_students.php';</script>";
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
    $avail = $availability - 1;

    $issued_date = date('Y-m-d');
    $return_date = date('Y-m-d', strtotime('+15 days')); // Assuming a 15-day return period

    // Insert into issued_books table
    $issue_query = "INSERT INTO issued_books (issu_id, stu_id, username, rollno, email, selectyourdegree, selectyourcourse,currectstudyingyear, bid, accession_number, title, book_keyword, author, book_image, copies, publication, publisher, isbn, availability, price, cupboard_name, shelve_name, issued_date, return_date) 
    VALUES (NULL, '$stu_id', '$username', '$rollno', '$email', '$selectyourdegree', '$selectyourcourse','$currectstudyingyear', '$bid', '$accession_number', '$title', '$book_keyword', '$author', '$book_image', '$copies', '$publication', '$publisher', '$isbn', '$avail', '$price', '$cupboard_name', '$shelve_name', '$issued_date', '$return_date')";

    if (mysqli_query($conn, $issue_query)) {
        // Update book availability
        $update_query = "UPDATE add_books SET availability = '$avail' WHERE bid='$bid'";
        mysqli_query($conn, $update_query);

        // Delete from temp_borrow_book_request
        $delete_query = "DELETE FROM temp_borrow_book_request WHERE bid='$bid' AND stu_id='$stu_id'";
        mysqli_query($conn, $delete_query);

        echo "<script>alert('Book issued successfully!'); window.location.href='provide_books_students.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error issuing book: " . mysqli_error($conn) . "'); window.location.href='provide_books_students.php';</script>";
    }
} else {
    header("Location: provide_books_students.php");
    exit();
}
?>