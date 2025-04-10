<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

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

// Initialize feedback variables
$feedback_message = '';
$feedback_class = '';

if (isset($_POST["register"])) {
    $name = $_POST["name"];
    $pfno = $_POST["pfno"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (!empty($name) && !empty($pfno) && !empty($email) && !empty($password)) {
        $q = "SELECT * FROM staff_acc_create WHERE pfno='$pfno'";
        $result = mysqli_query($conn, $q);

        if (mysqli_num_rows($result) == 0) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $q = "INSERT INTO staff_acc_create (staff_id, name, pfno, email, password) VALUES (NULL, '$name', '$pfno', '$email', '$hashed_password')";
            $result = mysqli_query($conn, $q);

            if ($result) {
                $feedback_message = "User <strong>$name</strong> is created successfully!";
                $feedback_class = 'alert-success';
                header("Refresh: 2; url=lib_add_staff_home.php");
            } else {
                $feedback_message = "An error occurred while creating the user. Please try again.";
                $feedback_class = 'alert-danger';
            }
        } else {
            $feedback_message = "PF Number <strong>$pfno</strong> already exists. Try a different number.";
            $feedback_class = 'alert-warning';
        }
    } else {
        $feedback_message = "Please fill all the details.";
        $feedback_class = 'alert-warning';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Account Creation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('staffreg.jpg'); /* Your background image */
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: #ffffff !important;
            font-weight: bold;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .form-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
        }

        .form-label {
            font-weight: bold;
            color: #495057;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            padding: 10px;
            font-weight: bold;
        }

        .btn-custom:hover {
            background-color: #218838;
        }

        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent black background */
            color: yellow;
            text-align: center;
            padding: 20px 0;
            margin-top: auto; /* Push footer to the bottom */
            width: 100%;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="librarian_home.php">Library Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="lib_add_staff_home.php">Back</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="form-title">Create Staff Account</h2>

                    <!-- Feedback Alert -->
                    <?php if (!empty($feedback_message)): ?>
                        <div class="alert <?php echo $feedback_class; ?> text-center" role="alert">
                            <?php echo $feedback_message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" onsubmit="return validateForm()">
                        <!-- Staff Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Staff Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Staff Name" required>
                            <div id="name-error" class="error-message"></div>
                        </div>

                        <!-- PF Number -->
                        <div class="mb-3">
                            <label for="pfno" class="form-label">PF Number</label>
                            <input type="text" class="form-control" id="pfno" name="pfno" placeholder="Enter Staff PF No" required>
                            <div id="pfno-error" class="error-message"></div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Staff Email" required>
                            <div id="email-error" class="error-message"></div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                            <div id="password-error" class="error-message"></div>
                        </div>

                        <button type="submit" class="btn btn-custom w-100" name="register">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<br>
    <footer>
        <p>&copy; 2024 Government Arts College, C. Mutlur Chidambaram. Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
    </footer>

    <script>
        // Real-time validation for Staff Name
        document.getElementById("name").addEventListener("input", function () {
            let name = this.value;
            let errorElement = document.getElementById("name-error");
            let lettersRegex = /^[a-zA-Z\s]+$/;

            if (!lettersRegex.test(name)) {
                errorElement.textContent = "Staff Name must contain only letters and spaces!";
            } else {
                errorElement.textContent = "";
            }
        });

        // Real-time validation for PF Number
        document.getElementById("pfno").addEventListener("input", function () {
            let pfno = this.value;
            let errorElement = document.getElementById("pfno-error");
            let pfnoRegex = /^[a-zA-Z0-9]+$/;

            if (!pfnoRegex.test(pfno)) {
                errorElement.textContent = "PF Number must be alphanumeric!";
            } else {
                errorElement.textContent = "";
            }
        });

        // Real-time validation for Email
        document.getElementById("email").addEventListener("input", function () {
            let email = this.value;
            let errorElement = document.getElementById("email-error");
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                errorElement.textContent = "Please enter a valid email address!";
            } else {
                errorElement.textContent = "";
            }
        });

        // Real-time validation for Password
        document.getElementById("password").addEventListener("input", function () {
            let password = this.value;
            let errorElement = document.getElementById("password-error");
            let passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

            if (!passwordRegex.test(password)) {
                errorElement.textContent = "Password must be at least 8 characters long and contain at least one letter and one number!";
            } else {
                errorElement.textContent = "";
            }
        });

        // Form submission validation
        function validateForm() {
            let isValid = true;

            // Validate Staff Name
            let name = document.getElementById("name").value;
            let lettersRegex = /^[a-zA-Z\s]+$/;
            if (!lettersRegex.test(name)) {
                document.getElementById("name-error").textContent = "Staff Name must contain only letters and spaces!";
                isValid = false;
            }

            // Validate PF Number
            let pfno = document.getElementById("pfno").value;
            let pfnoRegex = /^[a-zA-Z0-9]+$/;
            if (!pfnoRegex.test(pfno)) {
                document.getElementById("pfno-error").textContent = "PF Number must be alphanumeric!";
                isValid = false;
            }

            // Validate Email
            let email = document.getElementById("email").value;
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById("email-error").textContent = "Please enter a valid email address!";
                isValid = false;
            }

            // Validate Password
            let password = document.getElementById("password").value;
            let passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
            if (!passwordRegex.test(password)) {
                document.getElementById("password-error").textContent = "Password must be at least 8 characters long and contain at least one letter and one number!";
                isValid = false;
            }

            return isValid;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>