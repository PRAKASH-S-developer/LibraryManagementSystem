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
if (isset($_GET['bid']) && isset($_GET['staff_id'])) {
    $bid = $_GET['bid'];
    $staff = $_GET['staff_id'];

    // Fetch student and book details
    $query = "SELECT * FROM temp_staffs_borrow_book_request WHERE bid='$bid' AND staff_id='$staff'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Prepare data for insertion
       // $tid = uniqid("TID_"); // Generate unique transaction ID
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
        $cupboard_name = $row["cupboard_name"];
        $shelve_name = $row["shelve_name"];
        $avil=$availability-1;
        
        // Set issue and return dates
        $issue_date = date("Y-m-d"); // Today's date
        $return_date = date("Y-m-d", strtotime("+90 days")); // 90 days later

        // Insert into issued books table
        $insertQuery = "INSERT INTO issued_books_staffs 
            (staff_b_issu_id, staff_id, name, pfno, email, bid,
            accession_number, title, book_keyword, author, book_image, copies, publication,
            publisher, isbn, availability, price, cupboard_name, shelve_name, issue_date, return_date) 
            VALUES 
            (NULL, '$staff', '$name', '$pfno', '$email', '$bid',
            '$accession_number', '$title', '$book_keyword', '$author', '$book_image', '$copies', '$publication',
            '$publisher', '$isbn', '$avil', '$price', '$cupboard_name', '$shelve_name', '$issue_date', '$return_date')";

        if (mysqli_query($conn, $insertQuery)) {
            // Decrease book availability count in 'add_books' table
            $updateAvailabilityQuery = "UPDATE add_books SET availability = availability - 1 WHERE bid = '$bid'";
            mysqli_query($conn, $updateAvailabilityQuery);

            // Delete from temp_borrow_book_request after successful insertion
            $deleteQuery = "DELETE FROM temp_staffs_borrow_book_request WHERE bid='$bid' AND staff_id='$staff'";
            mysqli_query($conn, $deleteQuery);

            echo "<script>alert('Book issued successfully!'); window.location='librarian_book_issue_staffs.php';</script>";
        } else {
            echo "<script>alert('Error issuing book.'); window.location='librarian_book_issue_staffs.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid book or staff ID.'); window.location='librarian_book_issue_staffs.php';</script>";
    }
} else {
    echo "<script>alert('Missing book or staff ID.'); window.location='librarian_book_issue_staffs.php';</script>";
}
?>
