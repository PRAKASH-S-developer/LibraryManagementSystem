<?php
// Database Connection
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Get today's date and calculate 3 days ahead
$current_date = date('Y-m-d');
$three_days_later = date('Y-m-d', strtotime('+3 days'));

// Query to find students whose book return date is within the next 3 days
$query = "SELECT * FROM issued_books_staffs WHERE return_date BETWEEN '$current_date' AND '$three_days_later'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $email = $row['email'];
        $username = $row['name'];
        $title = $row['title'];
        $return_date = $row['return_date'];

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
            $mail->Body = "Dear $username,\n\nYour borrowed book '$title' is due on $return_date.\nPlease return it within the next 3 days to avoid penalties.\n\nThank you,\nLibrary Management System";

            // Send Email
            $mail->send();
            //echo "Reminder sent to $email for book '$title'.<br>";
        } catch (Exception $e) {
            //echo "Error sending email: {$mail->ErrorInfo}";
        }
    }
} else {
    //echo "No upcoming book return deadlines in the next 3 days.";
}

mysqli_close($conn);
?>
