<?php
//session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

// Redirect to login page if the user is not logged in (optional, for security)
if (!isset($_SESSION["name"]) || !isset($_SESSION["pfno"]) || !isset($_SESSION["email"])) {
    header("location:admin_login.php");
    exit();
}

// Fetch all students from the database
$query = "SELECT stu_id, college_join_date, selectyourdegree, currectstudyingyear FROM st_acc_create";
$result = mysqli_query($conn, $query);

if ($result) {
    $current_date = new DateTime(); // Get the current date
    $updated_count = 0; // Counter for updated records

    // Loop through each student
    while ($row = mysqli_fetch_assoc($result)) {
        $stu_id = $row['stu_id'];
        $college_join_date = new DateTime($row['college_join_date']);
        $selectyourdegree = $row['selectyourdegree'];
        $current_studying_year = $row['currectstudyingyear'];

        // Calculate the difference between the current date and the join date
        $interval = $current_date->diff($college_join_date);
        $years_passed = $interval->y;

        // Determine degree duration based on the selected degree
        if ($selectyourdegree == "B.Sc." || $selectyourdegree == "B.C.A") {
            $degree_duration = 3; // 3 years for B.Sc. and B.C.A
        } else if ($selectyourdegree == "M.Sc.") {
            $degree_duration = 2; // 2 years for M.Sc.
        }

        // Calculate the current studying year
        $new_studying_year = min($years_passed + 1, $degree_duration);

        // If years passed exceed degree duration, mark as "Passed Out"
        if ($new_studying_year > $degree_duration) {
            $new_studying_year = 'Passed Out';
        }

        // Update the database if the current studying year has changed
        if ($new_studying_year != $current_studying_year) {
            $update_query = "UPDATE st_acc_create SET currectstudyingyear = '$new_studying_year' WHERE stu_id = '$stu_id'";
            mysqli_query($conn, $update_query);
            $updated_count++;
        }
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <!-- Auto Refresh Every 5 Hours -->
    <script>
        setTimeout(function() {
            location.reload();
        }, 18000000); // 5 hours = 18000000 milliseconds
    </script>
</head>
<body>

</body>
</html>
