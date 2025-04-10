<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the librarian is logged in
if (!isset($_SESSION["name"])) {
    header("location:librarian_login.php");
    exit();
}

// Check if bid and stu_id are received
if (isset($_GET['bid']) && isset($_GET['stu_id'])) {
    $bid = mysqli_real_escape_string($conn, $_GET['bid']);
    $stu_id = mysqli_real_escape_string($conn, $_GET['stu_id']);

    // Fetch student details
    $query = "SELECT * FROM int_temp_return_book_request WHERE bid='$bid' AND stu_id='$stu_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Invalid book or student ID.'); window.location='librarian_book_return.php';</script>";
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    $username = $row["username"];
    $rollno = $row["rollno"];
    $email = $row["email"];
    $selectyourdegree = $row["selectyourdegree"];
    $selectyourcourse = $row["selectyourcourse"];
    $accession_number = $row["accession_number"];
    $title = $row["title"];
    $book_keyword = $row["book_keyword"];
    $author = $row["author"];
    $book_image = $row["book_image"];
    $copies = $row["copies"];
    $publication = $row["publication"];
    $publisher = $row["publisher"];
    $isbn = $row["isbn"];
    $availability = $row["availability"];
    $price = $row["price"];
    $cupboard_name = $row["cupboard_name"];
    $shelve_name = $row["shelve_name"];
    $issued_date = $row["issued_date"];
    $currectstudyingyear = $row["currectstudyingyear"];
    $avil = $availability + 1;

    // Set return date
    $returned_date = date("Y-m-d");

    // Insert into returned_books table
    $insertQuery = "INSERT INTO returned_books 
        (return_id, stu_id, username, rollno, email, selectyourdegree, selectyourcourse, currectstudyingyear, bid,
        accession_number, title, book_keyword, author, book_image, copies, publication,
        publisher, isbn, availability, price, cupboard_name, shelve_name, issued_date, returned_date) 
        VALUES 
        (NULL, '$stu_id', '$username', '$rollno', '$email', '$selectyourdegree', '$selectyourcourse',
         '$currectstudyingyear', '$bid',
        '$accession_number', '$title', '$book_keyword', '$author', '$book_image', '$copies', '$publication',
        '$publisher', '$isbn', '$avil', '$price', '$cupboard_name', '$shelve_name', '$issued_date', '$returned_date')";

    if (mysqli_query($conn, $insertQuery)) {
        // Update book availability
        $updateAvailabilityQuery = "UPDATE add_books SET availability = $avil WHERE bid = '$bid'";
        if (!mysqli_query($conn, $updateAvailabilityQuery)) {
            echo "<script>alert('Error updating book availability.'); window.location='librarian_book_return.php';</script>";
            exit();
        }

        // Delete from temp_return_book_request
        $deleteQuery = "DELETE FROM int_temp_return_book_request WHERE bid='$bid' AND stu_id='$stu_id'";
        if (!mysqli_query($conn, $deleteQuery)) {
            echo "<script>alert('Error deleting return request.'); window.location='librarian_book_return.php';</script>";
            exit();
        }

        echo "<script>alert('Book returned successfully!'); window.location='librarian_book_return.php';</script>";
    } else {
        // Log the error for debugging
        error_log("Error in INSERT query: " . mysqli_error($conn));
        echo "<script>alert('Error returning book. Please check the logs for more details.'); window.location='librarian_book_return.php';</script>";
    }
} else {
    echo "<script>alert('Missing book or student ID.'); window.location='librarian_book_return.php';</script>";
}

mysqli_close($conn);
?>