<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION["name"])) {
    header("location:admin_login.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "lms");

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the delete parameter is set in the URL
if (isset($_GET["delete"])) {
    $stu_id = $_GET["delete"];

    // Prepare the delete query
    $delete_query = "DELETE FROM st_acc_create WHERE stu_id = $stu_id";

    // Execute the delete query
    if (mysqli_query($conn, $delete_query)) {
        // If deletion is successful, show a success message and redirect
        echo "<script>alert('Student deleted successfully!');</script>";
        echo '<meta http-equiv="refresh" content="0; url=view_students.php"/>';
    } else {
        // If deletion fails, show an error message
        echo "<script>alert('Error deleting student: " . mysqli_error($conn) . "');</script>";
    }
} else {
    // If the delete parameter is not set, redirect to the view students page
    echo "<script>alert('Invalid request.');</script>";
    echo '<meta http-equiv="refresh" content="0; url=add_students_home.php"/>';
}

// Close the database connection
mysqli_close($conn);
?>