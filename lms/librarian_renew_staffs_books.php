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

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $staff_id = $_GET['staff_id'];

    // Fetch book and staff details before deletion
    $fetch_query = "SELECT * FROM temp_staffs_renew_book_request WHERE bid='$id' AND staff_id='$staff_id'";
    $fetch_result = mysqli_query($conn, $fetch_query);

    if (!$fetch_result || mysqli_num_rows($fetch_result) == 0) {
        echo "<script>alert('Book or staff not found.'); window.location.href='librarian_renew_staffs_books.php';</script>";
        exit();
    }

    $row = mysqli_fetch_assoc($fetch_result);
    $email = $row['email'];
    $name = $row['name'];
    $title = $row['title'];
    $due_date = $row['due_date'];

    // Send email notification
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
        $mail->Body = "Dear $name,\n\nYour Renewal Request For Book - [ $title ] - is Rejected. Create A Return Request From Your Dashboard and Please Return Your Book on Date : [ $due_date ] \n\n\nThank you,\nLibrary Management System.\n\n Designed & Developed By PRAKASH S, 2 M.Sc. Computer Science [2023 - 2025] Batch.";

        // Send Email
        $mail->send();

        // Delete the record from the database
        $delete_query = "DELETE FROM temp_staffs_renew_book_request WHERE bid='$id' AND staff_id='$staff_id'";
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>alert('Renewal Rejected successfully! & Email Notification Sent Successfully!.'); window.location.href='librarian_renew_staffs_books.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error deleting record: " . mysqli_error($conn) . "');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Email could not be sent. Error: " . $mail->ErrorInfo . "'); window.location.href='librarian_renew_staffs_books.php';</script>";
    }
}

// Fetch all books
$query = "SELECT * FROM temp_staffs_renew_book_request";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { 
            background-color: rgb(84, 109, 135); 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            margin: 0; 
            padding: 0; 
            font-family: Arial, sans-serif;
        }
        .navbar { 
            background-color: #343a40; 
            padding: 10px 0;
        }
        .navbar-brand, .navbar a { 
            color: #ffffff !important; 
        }
        .container { 
            flex: 1; 
            padding: 20px; 
            margin: 0; 
            max-width: 100%; 
        }
        .table-container {
            overflow-x: auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0;
            padding: 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 0;
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            vertical-align: middle; 
            border-bottom: 1px solid #ddd;
        }
        th { 
            background-color: #343a40; 
            color: #fff; 
            position: sticky; 
            top: 0; 
            z-index: 1;
            text-align: center;
        }
        tr:hover { 
            background-color: #f5f5f5; 
        }
        footer { 
            background-color: black; 
            color: yellow; 
            text-align: center; 
            padding: 10px 0; 
            margin-top: auto; 
        }
        .search-bar {
            width: 100%;
            max-width: 400px;
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
            font-size: 16px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        }
        .btn-action {
            margin: 2px;
        }
    </style>
    <script>
        $(document).ready(function() {
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#bookTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="#">Library Management System</a>
        <form method="post">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>&nbsp;&nbsp;&nbsp;
            <a href="librarian_book_issue_staffs.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</nav>

<div class="container mt-3">
    <h2 class="text-center mb-4 text-warning">📚 All Books</h2>
    
    <input type="text" id="search" class="search-bar" placeholder="Search by any field...">
    
    <div class="table-container">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Book ID</th>
                    <th>Book Cover</th>
                    <th>Accession No</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Available Copies</th>
                    <th>Staff Name</th>
                    <th>Pf No</th>
                    <th>Book Issued Date</th>
                    <th>Expected Return Date</th>
                    <th>New Return Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookTable">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['bid']; ?></td>
                        <td><img src="<?php echo $row['book_image']; ?>" alt="Book Cover" width="80" height="100"></td>
                        <td><?php echo $row['accession_number']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td><?php echo $row['publisher']; ?></td>
                        <td><?php echo $row['availability']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['pfno']; ?></td>
                        <td><?php echo $row['issued_date']; ?></td>
                        <td><?php echo $row['due_date']; ?></td>
                        <td><?php echo $row['new_return_date']; ?></td>
                        <td>
                            <a href="renew_staff_book_success.php?bid=<?php echo $row['bid']; ?>&staff_id=<?php echo $row['staff_id']; ?>" class="btn btn-sm btn-primary btn-action">Renew</a><br><br>
                            <a href="?delete=<?php echo $row['bid']; ?>&staff_id=<?php echo $row['staff_id']; ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Are you sure you want to reject this renewal request?');">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>