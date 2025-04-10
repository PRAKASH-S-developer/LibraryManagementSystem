<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

// Check if the user is logged in
if (!isset($_SESSION["name"]) || !isset($_SESSION["pfno"]) || !isset($_SESSION["email"])) {
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

// Fetch student details for update
if (isset($_GET["id"])) {
    $stu_id = $_GET["id"];
    $query = "SELECT * FROM st_acc_create WHERE stu_id = $stu_id";
    $result = mysqli_query($conn, $query);
    $student = mysqli_fetch_assoc($result);
}

// Update student logic
if (isset($_POST["update"])) {
    $stu_id = $_POST["stu_id"];
    $username = $_POST["username"];
    $selectyourdegree = $_POST["selectyourdegree"];
    $selectyourcourse = $_POST["selectyourcourse"];
    $rollno = $_POST["rollno"];
    $email = $_POST["email"];
    $number = $_POST["number"];
    $address = $_POST["address"];
    $batch_starting_year = $_POST["batch_starting_year"];
    $batch_ending_year = $_POST["batch_ending_year"];
    $college_join_date = $_POST["college_join_date"];
    $currectstudyingyear = $_POST["currectstudyingyear"];

    $update_query = "UPDATE st_acc_create SET 
        username = '$username', 
        selectyourdegree = '$selectyourdegree', 
        selectyourcourse = '$selectyourcourse', 
        rollno = '$rollno', 
        email = '$email', 
        number = '$number', 
        address = '$address', 
        batch_starting_year = '$batch_starting_year', 
        batch_ending_year = '$batch_ending_year', 
        college_join_date = '$college_join_date',
        currectstudyingyear = '$currectstudyingyear'
        WHERE stu_id = $stu_id";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Student updated successfully!');</script>";
        echo '<meta http-equiv="refresh" content="1; url=lib_delete_update_students.php"/>';
    } else {
        echo "<script>alert('Error updating student.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(84, 109, 135);
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="form-title">Update Student</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="stu_id" value="<?= $student['stu_id']; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Student Name</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= $student['username']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="degree">Select Degree</label>
                                    <select class="form-control" name="selectyourdegree" id="degree" required>
                                        <option value="B.Sc." <?= $student['selectyourdegree'] == 'B.Sc.' ? 'selected' : ''; ?>>B.Sc.</option>
                                        <option value="B.C.A" <?= $student['selectyourdegree'] == 'B.C.A' ? 'selected' : ''; ?>>B.C.A</option>
                                        <option value="M.Sc." <?= $student['selectyourdegree'] == 'M.Sc.' ? 'selected' : ''; ?>>M.Sc.</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="course">Select Course</label>
                                    <select class="form-control" name="selectyourcourse" id="course" required>
                                        <option value="Computer Science" <?= $student['selectyourcourse'] == 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                        <option value="Computer Application" <?= $student['selectyourcourse'] == 'Computer Application' ? 'selected' : ''; ?>>Computer Application</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currectstudyingyear">Current Studying Year</label>
                                    <select class="form-control" name="currectstudyingyear" id="currectstudyingyear" required>
                                        <option value="1 Year" <?= $student['currectstudyingyear'] == '1 Year' ? 'selected' : ''; ?>>1 Year</option>
                                        <option value="2 Year" <?= $student['currectstudyingyear'] == '2 Year' ? 'selected' : ''; ?>>2 Year</option>
                                        <option value="3 Year" <?= $student['currectstudyingyear'] == '3 Year' ? 'selected' : ''; ?>>3 Year</option>
                                        <option value="Passed Out" <?= $student['currectstudyingyear'] == 'Passed Out' ? 'selected' : ''; ?>>Passed Out</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rollno">Roll No</label>
                                    <input type="text" class="form-control" id="rollno" name="rollno" value="<?= $student['rollno']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= $student['email']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="number">Mobile Number</label>
                                    <input type="tel" class="form-control" id="number" name="number" value="<?= $student['number']; ?>" pattern="[0-9]{10}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required><?= $student['address']; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="college_join_date">College Join Date</label>
                                    <input type="date" class="form-control" id="college_join_date" name="college_join_date" value="<?= $student['college_join_date']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Batch Starting Year</label>
                                    <select name="batch_starting_year" class="form-control" required>
                                        <?php
                                        $currentYear = date("Y");
                                        for ($year = $currentYear + 10; $year >= $currentYear - 10; $year--) {
                                            $selected = ($year == $student['batch_starting_year']) ? 'selected' : '';
                                            echo "<option value='$year' $selected>$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Batch Ending Year</label>
                                    <select name="batch_ending_year" class="form-control" required>
                                        <?php
                                        $currentYear = date("Y");
                                        for ($year = $currentYear + 10; $year >= $currentYear - 10; $year--) {
                                            $selected = ($year == $student['batch_ending_year']) ? 'selected' : '';
                                            echo "<option value='$year' $selected>$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-custom w-100" name="update">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>