<?php
session_start();
if (isset($_SESSION["name"]) && isset($_SESSION["pfno"]) && isset($_SESSION["email"])) {
    header("location:staff_home.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "lms");

// Login logic
$message = "";
$alertType = "";

if (isset($_POST["login"])) {
    $name = $_POST["name"];
    $pfno = $_POST["pfno"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (!empty($name) && !empty($pfno) && !empty($email) && !empty($password)) {
        $q = "SELECT * FROM staff_acc_create WHERE pfno='$pfno'";
        $result = mysqli_query($conn, $q);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verify hashed password
            if (password_verify($password, $row['password'])) {
                $_SESSION['name'] = $row['name'];
                $_SESSION['pfno'] = $row['pfno'];
                $_SESSION['email'] = $row['email'];

                $message = "Login successful! Redirecting...";
                $alertType = "success";
                echo '<meta http-equiv="refresh" content="2; url=staff_home.php"/>';
            } else {
                $message = "Invalid password. Please try again.";
                $alertType = "danger";
            }
        } else {
            $message = "Username not found. Please check your details.";
            $alertType = "danger";
        }
    } else {
        $message = "Please fill in all the details.";
        $alertType = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('staffreg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
        }

        .container {
            margin-top: 50px;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
        }

        .btn-custom:hover {
            background-color: #218838;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle input {
            padding-right: 40px; /* Add padding to the right for the eye icon */
        }

        .password-toggle i {
            position: absolute;
            top: 73%;
            right: 15px; /* Adjust the right position */
            transform: translateY(-50%);
            cursor: pointer;
            color: #495057;
        }

        footer {
            background-color: black;
            color: yellow;
            text-align: center;
            padding: 10px 0;
            margin-top: 150px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="form-title">Staff Login</h2>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Staff Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your username" required>
                        </div>

                        <div class="mb-3">
                            <label for="rollno" class="form-label">PF Number</label>
                            <input type="text" class="form-control" id="pfno" name="pfno" placeholder="Enter Your PF Number" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Staff Email" required>
                        </div>

                        <div class="mb-3 password-toggle">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </div>

                        <button type="submit" class="btn btn-custom w-100" name="login">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc.Computer Science</p>
    </footer>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById("togglePassword");
        const password = document.getElementById("password");

        togglePassword.addEventListener("click", function () {
            // Toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            // Toggle the eye icon
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>
</html>