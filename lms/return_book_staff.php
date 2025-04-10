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

// Check if bid and staff_id are received
if (isset($_GET['bid']) && isset($_GET['staff_id'])) {
    $bid = mysqli_real_escape_string($conn, $_GET['bid']);
    $staff_id = mysqli_real_escape_string($conn, $_GET['staff_id']);

    // Fetch staff details
    $query = "SELECT * FROM temp_staffs_return_book_request WHERE bid='$bid' AND staff_id='$staff_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Invalid book or staff ID.'); window.location='librarian_book_return_staff.php';</script>";
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    $name = $row["name"];
    $pfno = $row["pfno"];
    $email = $row["email"];
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
    $due_date = $row["due_date"];
    $cupboard_name = $row["cupboard_name"];
    $shelve_name = $row["shelve_name"];
    $avail=$availability+1;
    // Set return date
    $returned_date = date("Y-m-d");


    $result10 = mysqli_query($conn, "SELECT issue_date FROM issued_books_staffs WHERE pfno='$pfno' AND staff_id='$staff_id' AND bid='$bid'");

    if (mysqli_num_rows($result10) > 0) {
        $r10 = mysqli_fetch_assoc($result10);
        $issued_date = $r10["issue_date"];
    } else {
        die("User not found in database.");
    }

    // Set return date
    $returned_date = date("Y-m-d");



    // Insert into returned_books table
    $insertQuery = "INSERT INTO returned_books_staffs 
        (return_id_staff, staff_id, name, pfno, email, bid,accession_number,title,book_keyword,author,
         book_image, copies, publication,publisher, isbn, availability, price, cupboard_name, shelve_name, issued_date, returned_date) 
        VALUES 
        (NULL, '$staff_id', '$name', '$pfno', '$email', '$bid','$accession_number', '$title', '$book_keyword', 
        '$author', '$book_image', '$copies', '$publication',
        '$publisher', '$isbn', '$avail', '$price', '$cupboard_name', '$shelve_name', '$issued_date', '$returned_date')";

    if (mysqli_query($conn, $insertQuery)) {
        // Update book availability
        $updateAvailabilityQuery = "UPDATE add_books SET availability = $avail WHERE bid = '$bid'";
        if (!mysqli_query($conn, $updateAvailabilityQuery)) {
            echo "<script>alert('Error updating book availability.'); window.location='librarian_book_return_staff.php';</script>";
            exit();
        }

        // Delete from temp_return_book_request
        $deleteQuery = "DELETE FROM temp_staffs_return_book_request WHERE bid='$bid' AND staff_id='$staff_id'";
        if (!mysqli_query($conn, $deleteQuery)) {
            echo "<script>alert('Error deleting return request.'); window.location='librarian_book_return_staff.php';</script>";
            exit();
        }

        echo "<script>alert('Book returned successfully!'); window.location='librarian_book_return_staff.php';</script>";
    } else {
        // Log the error for debugging
        error_log("Error in INSERT query: " . mysqli_error($conn));
        echo "<script>alert('Error returning book. Please check the logs for more details.'); window.location='librarian_book_return_staff.php';</script>";
    }
} else {
    echo "<script>alert('Missing book or student ID.'); window.location='librarian_book_return_staff.php';</script>";
}

mysqli_close($conn);
?>
