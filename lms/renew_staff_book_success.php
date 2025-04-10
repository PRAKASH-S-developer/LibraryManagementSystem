<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION["name"])) {
    header("location: librarian_login.php");
    exit();
}

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Handle logout request
if (isset($_POST["logout"])) {
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session
    header("location: index.html");
    exit();
}

// Retrieve book and staff information
if (isset($_GET['bid']) && isset($_GET['staff_id'])) {
    $bid = mysqli_real_escape_string($conn, $_GET['bid']);
    $staff_id = mysqli_real_escape_string($conn, $_GET['staff_id']);

    // Fetch book details from issued_books_staffs table
    $book_query = "SELECT * FROM issued_books_staffs WHERE bid='$bid' AND staff_id='$staff_id'";
    $book_result = mysqli_query($conn, $book_query);

    if (!$book_result || mysqli_num_rows($book_result) == 0) {
        echo "<script>alert('Book or staff not found.'); window.location.href='librarian_renew_staffs_books.php';</script>";
        exit();
    }

    $book_row = mysqli_fetch_assoc($book_result);

    $name = $book_row["name"];
    $pfno = $book_row["pfno"];
    $email = $book_row["email"];
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
    $issued_date = $book_row["issue_date"];
    $exp_return_date = $book_row["return_date"];

    $renewed_date = date('Y-m-d');

    // Fetch renewal book details from temp_staffs_renew_book_request table
    $renew_book_query = "SELECT * FROM temp_staffs_renew_book_request WHERE bid='$bid' AND staff_id='$staff_id'";
    $renew_book_result = mysqli_query($conn, $renew_book_query);

    if (!$renew_book_result || mysqli_num_rows($renew_book_result) == 0) {
        echo "<script>alert('Book or staff not found in renewal requests.'); window.location.href='librarian_renew_staffs_books.php';</script>";
        exit();
    }

    $renew_book_row = mysqli_fetch_assoc($renew_book_result);
    $new_return_date = $renew_book_row["new_return_date"];

    // Insert into staff_renewed_books table
    $issue_query = "INSERT INTO staff_renewed_books 
        (renewal_staff_id, staff_id, name, pfno, email, bid, accession_number, title, 
        book_keyword, author, book_image, copies, publication, publisher, isbn, availability, price, 
        cupboard_name, shelve_name, issued_date, exp_return_date, renewed_date, new_return_date) 
        VALUES 
        (NULL, '$staff_id', '$name', '$pfno', '$email', '$bid', '$accession_number', '$title',
        '$book_keyword', '$author', '$book_image', '$copies', '$publication', '$publisher', '$isbn',
        '$availability', '$price', '$cupboard_name', '$shelve_name',
        '$issued_date', '$exp_return_date', '$renewed_date', '$new_return_date')";

    if (mysqli_query($conn, $issue_query)) {
        // Update return date in issued_books_staffs table
        $update_query = "UPDATE issued_books_staffs SET return_date = '$new_return_date' WHERE bid='$bid' AND staff_id='$staff_id'";
        mysqli_query($conn, $update_query);

        // Delete from temp_staffs_renew_book_request table
        $delete_query = "DELETE FROM temp_staffs_renew_book_request WHERE bid='$bid' AND staff_id='$staff_id'";
        mysqli_query($conn, $delete_query);

        // Prepare Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'techtamilprakash@gmail.com'; // Your Gmail
            $mail->Password = 'gPuK sMwSe WqkFK zqtZAB'; // App Password (Enable 2FA & Create App Password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email Content
            $mail->setFrom('techtamilprakash@gmail.com', 'Library Management System');
            $mail->addAddress($email, $name);
            $mail->Subject = "Library Book Renewal Status";
            $mail->Body = "Dear $name,\n\nYour Renewal Request For Book - [ $title ] - is approved. Your New Return Date : [ $new_return_date ] \n\n\nThank you,\nLibrary Management System.\n\n Designed & Developed By PRAKASH S, 2 M.Sc. Computer Science [2023 - 2025] Batch.";

            // Send Email
            $mail->send();

            echo "<script>alert('Book Renewed successfully! & Email Notification Sent Successfully!.'); window.location.href='librarian_renew_staffs_books.php';</script>";
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Book Renewed successfully, but email could not be sent. Error: " . $mail->ErrorInfo . "'); window.location.href='librarian_renew_staffs_books.php';</script>";
        }
    } else {
        echo "<script>alert('Error Renewing book: " . mysqli_error($conn) . "'); window.location.href='librarian_renew_staffs_books.php';</script>";
    }
} else {
    header("Location: librarian_renew_staffs_books.php");
    exit();
}
?>