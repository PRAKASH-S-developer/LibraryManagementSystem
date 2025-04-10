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

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Check if bid, stu_id, and issue_date are received
if (isset($_POST['bid']) && isset($_POST['stu_id']) && isset($_POST['issue_date'])) {
    $bid = $_POST['bid'];
    $stu_id = $_POST['stu_id'];
    $issue_date = $_POST['issue_date'];

    // Fetch student and book details
    $query = "SELECT * FROM int_temp_borrow_book_request WHERE bid='$bid' AND stu_id='$stu_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Prepare data for insertion
        $username = $row["username"];
        $rollno = $row["rollno"];
        $email = $row["email"];
        $selectyourdegree = $row["selectyourdegree"];
        $selectyourcourse = $row["selectyourcourse"];
        $currectstudyingyear = $row["currectstudyingyear"];
        $accession_number = $row["accession_number"];
        $title = $row["title"];
        $book_keyword = $row["book_keyword"];
        $author = $row["author"];
        $book_image = $row["book_image"];
        $copies = $row["copies"];
        $publication = $row["publication"];
        $publisher = $row["publisher"];
        $isbn = $row["isbn"];
        $avil = $row["availability"];
        $price = $row["price"];
        $cupboard_name = $row["cupboard_name"];
        $shelve_name = $row["shelve_name"];

        // Insert into issued books table
        $insertQuery = "INSERT INTO temp_borrow_book_request 
            (tid, stu_id, username, rollno, email, selectyourdegree, selectyourcourse, currectstudyingyear,  bid,
            accession_number, title, book_keyword, author, book_image, copies, publication,
            publisher, isbn, availability, price, cupboard_name, shelve_name, issue_date) 
            VALUES 
            (NULL, '$stu_id', '$username', '$rollno', '$email', '$selectyourdegree', '$selectyourcourse','$currectstudyingyear', '$bid',
            '$accession_number', '$title', '$book_keyword', '$author', '$book_image', '$copies', '$publication',
            '$publisher', '$isbn', '$avil', '$price', '$cupboard_name', '$shelve_name', '$issue_date')";

            // Delete from temp_borrow_book_request after successful insertion
                $deleteQuery = "DELETE FROM int_temp_borrow_book_request WHERE bid='$bid' AND stu_id='$stu_id'";
                mysqli_query($conn, $deleteQuery);

        if (mysqli_query($conn, $insertQuery)) {
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
                $mail->addAddress($email, $username);
                $mail->Subject = "Library Book Return Reminder";
                $mail->Body = "Dear $username,\n\nYour Requested Book -[ $title ]- is approved for borrow. Please Collect Your Book On Date : [ $issue_date ] In Department Libarary.\nPlease Took Your ID Card With You While You Go To Department Library.\n\nThank you,\nLibrary Management System.\n\n Designed & Developed By PRAKASH S, 2 M.Sc. Computer Science [2023 - 2025] Batch.";

                // Send Email
                $mail->send();
                echo "<script>alert('Email Notification Sent successfully!'); window.location='librarian_book_issue.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Error sending email: {$mail->ErrorInfo}'); window.location='librarian_book_issue.php';</script>";
            }
        } else {
            echo "<script>alert('Error inserting data into the database: " . mysqli_error($conn) . "'); window.location='librarian_book_issue.php';</script>";
        }
    } else {
        echo "<script>alert('No data found for the given book and student ID.'); window.location='librarian_book_issue.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location='librarian_book_issue.php';</script>";
}
?>