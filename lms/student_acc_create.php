<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

// Redirect to login page if the user is not logged in
if (!isset($_SESSION["name"]) || !isset($_SESSION["pfno"]) || !isset($_SESSION["email"])) {
    header("location:admin_login.php");
    exit();
}

$name = $_SESSION["name"];
$pfno = $_SESSION["pfno"];
$email = $_SESSION["email"];

if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $selectyourdegree = $_POST["selectyourdegree"];
    $selectyourcourse = $_POST["selectyourcourse"];
    $rollno = $_POST["rollno"];
    $email = $_POST["email"];
    $number = $_POST["number"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $batch_starting_year = $_POST["batch_starting_year"];
    $batch_ending_year = $_POST["batch_ending_year"];
    $college_join_date = $_POST["college_join_date"];

    if (!empty($username) && !empty($selectyourdegree) && !empty($selectyourcourse) && !empty($rollno) && !empty($email) && !empty($number) && !empty($address) && !empty($password) && !empty($batch_starting_year) && !empty($batch_ending_year) && !empty($college_join_date)) {
        $q = "SELECT * FROM st_acc_create WHERE rollno='$rollno'";
        $result = mysqli_query($conn, $q);

        if (mysqli_num_rows($result) == 0) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Calculate current studying year based on join date and degree
            $join_date = new DateTime($college_join_date);
            $current_date = new DateTime();
            $interval = $current_date->diff($join_date);
            $years_passed = $interval->y;

            if ($selectyourdegree == "B.Sc." || $selectyourdegree == "B.C.A") {
                $degree_duration = 3;
            } else if ($selectyourdegree == "M.Sc.") {
                $degree_duration = 2;
            }

            $currectstudyingyear = min($years_passed + 1, $degree_duration);
            if ($currectstudyingyear > $degree_duration) {
                $currectstudyingyear = 'Passed Out';
            }

            $q = "INSERT INTO st_acc_create (stu_id, username, selectyourdegree, selectyourcourse, rollno, email, number, address, password, batch_starting_year, batch_ending_year, currectstudyingyear, college_join_date) VALUES (NULL, '$username', '$selectyourdegree', '$selectyourcourse', '$rollno', '$email', '$number', '$address', '$hashed_password', '$batch_starting_year', '$batch_ending_year', '$currectstudyingyear', '$college_join_date')";
            $result = mysqli_query($conn, $q);

            if ($result) {
                echo "<script>alert('User $username is created successfully');</script>";
                echo '<meta http-equiv="refresh" content="1; url=admin_home.php"/>';
            } else {
                echo "<script>alert('Error occurred while creating user. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Username already exists. Try a different name.');</script>";
        }
    } else {
        echo "<script>alert('Please fill all the details.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Account Creation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('streg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: #ffffff !important;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
        }

        .container {
            margin-top: 50px;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
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

        footer {
            background-color: black;
            color: yellow;
            text-align: center;
            padding: 10px 0;
            margin-top: 25px;
            width: 100%;
            position: relative;
            left: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            width: 100%;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }

        .row {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Header with Back Button -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_home.php">Library Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="add_students_home.php">Back</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="form-title">Create Account</h2>
                    <form method="POST" action="" onsubmit="return validateForm()">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Student Name</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Student Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="degree">Select Degree</label>
                                    <select class="form-control" name="selectyourdegree" id="degree" required onchange="updateCurrentStudyingYear()">
                                        <option value="">Select Degree</option>
                                        <option value="B.Sc.">B.Sc.</option>
                                        <option value="B.C.A">B.C.A</option>
                                        <option value="M.Sc.">M.Sc.</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="course">Select Course</label>
                                    <select class="form-control" name="selectyourcourse" id="course" required>
                                        <option value="">Select Course</option>
                                        <option value="Computer Science">Computer Science</option>
                                        <option value="Computer Application">Computer Application</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currectstudyingyear">Current Studying Year</label>
                                    <select class="form-control" name="currectstudyingyear" id="currectstudyingyear" required>
                                        <option value="">Select Year</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Batch Starting Year</label>
                                    <select name="batch_starting_year" class="form-control" required>
                                        <option value="">Select Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Batch Ending Year</label>
                                    <select name="batch_ending_year" class="form-control" required>
                                        <option value="">Select Year</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rollno">Roll No</label>
                                    <input type="text" class="form-control" id="rollno" name="rollno" placeholder="Enter Student Roll Number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Student Email" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="number">Mobile Number</label>
                                    <input type="tel" class="form-control" id="number" name="number" placeholder="Enter Student Mobile Number" pattern="[0-9]{10}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" placeholder="Enter Student Address" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="college_join_date">College Join Date</label>
                                    <input type="date" class="form-control" id="college_join_date" name="college_join_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-custom w-100" name="register">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc.Computer Science</p>
    </footer>

    <script>
        // JavaScript to populate the Batch Starting Year and Batch Ending Year dropdowns
        const populateYears = (selectName) => {
            const select = document.querySelector(`select[name='${selectName}']`);
            const currentYear = new Date().getFullYear();
            for (let year = currentYear + 10; year >= currentYear - 10; year--) {
                let option = document.createElement("option");
                option.value = year;
                option.textContent = year;
                select.appendChild(option);
            }
        };

        // Populate both dropdowns
        populateYears("batch_starting_year");
        populateYears("batch_ending_year");

        // Function to update the Current Studying Year dropdown based on the selected degree
        function updateCurrentStudyingYear() {
            const degreeSelect = document.getElementById("degree");
            const currentStudyingYearSelect = document.getElementById("currectstudyingyear");
            const selectedDegree = degreeSelect.value;

            // Clear existing options
            currentStudyingYearSelect.innerHTML = '<option value="">Select Year</option>';

            if (selectedDegree === "B.Sc." || selectedDegree === "B.C.A") {
                // Add options for B.Sc. and B.C.A
                const years = ["1 Year", "2 Year", "3 Year"];
                years.forEach(year => {
                    let option = document.createElement("option");
                    option.value = year;
                    option.textContent = year;
                    currentStudyingYearSelect.appendChild(option);
                });
            } else if (selectedDegree === "M.Sc.") {
                // Add options for M.Sc.
                const years = ["1 Year", "2 Year"];
                years.forEach(year => {
                    let option = document.createElement("option");
                    option.value = year;
                    option.textContent = year;
                    currentStudyingYearSelect.appendChild(option);
                });
            }
        }

        // Form Validation Function
        function validateForm() {
            let username = document.getElementById("username").value;
            let rollno = document.getElementById("rollno").value;
            let email = document.getElementById("email").value;
            let number = document.getElementById("number").value;
            let password = document.getElementById("password").value;
            let collegeJoinDate = document.getElementById("college_join_date").value;
            let errorMessage = "";

            // Regex for letters and spaces (for names)
            let lettersRegex = /^[a-zA-Z\s]+$/;

            // Regex for roll number (alphanumeric)
            let rollnoRegex = /^[a-zA-Z0-9]+$/;

            // Regex for email validation
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Regex for mobile number (10 digits)
            let numberRegex = /^\d{10}$/;

            // Regex for password (at least 8 characters, 1 uppercase, 1 lowercase, 1 number)
            let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

            // Validate Student Name
            if (!lettersRegex.test(username)) {
                errorMessage = "Student Name must contain only letters and spaces!";
            }

            // Validate Roll No
            else if (!rollnoRegex.test(rollno)) {
                errorMessage = "Roll No must be alphanumeric!";
            }

            // Validate Email
            else if (!emailRegex.test(email)) {
                errorMessage = "Invalid Email Address!";
            }

            // Validate Mobile Number
            else if (!numberRegex.test(number)) {
                errorMessage = "Mobile Number must be 10 digits!";
            }

            // Validate Password
            else if (!passwordRegex.test(password)) {
                errorMessage = "Password must be at least 8 characters long, contain 1 uppercase letter, 1 lowercase letter, and 1 number!";
            }

            // Validate College Join Date
            else if (!collegeJoinDate) {
                errorMessage = "Please select a valid College Join Date!";
            }

            if (errorMessage) {
                alert(errorMessage);
                return false;
            }
            return true;
        }
    </script>
</body>
</html>