<?php
session_start();
if (isset($_SESSION["name"]) && isset($_SESSION["pfno"]) && isset($_SESSION["email"])) {
    header("location:librarian_home.php");
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

    if (preg_match("/^[a-zA-Z ]+$/", $name) && preg_match("/^[0-9]+$/", $pfno) && !empty($email) && !empty($password)) {
        $q = "SELECT * FROM lib_acc_create WHERE pfno='$pfno' AND email='$email' AND name='$name'";
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
                echo '<meta http-equiv="refresh" content="2; url=librarian_home.php"/>';
            } else {
                $message = "Invalid password. Please try again.";
                $alertType = "danger";
            }
        } else {
            $message = "Username not found. Please check your details.";
            $alertType = "danger";
        }
    } else {
        $message = "Invalid input. Ensure name contains only letters and PF number contains only numbers.";
        $alertType = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validateForm() {
            let name = document.getElementById("name").value;
            let pfno = document.getElementById("pfno").value;
            
            let namePattern = /^[a-zA-Z ]+$/;
            let pfnoPattern = /^[0-9]+$/;
            
            if (!namePattern.test(name)) {
                alert("Librarian Name should only contain letters and spaces.");
                return false;
            }
            
            if (!pfnoPattern.test(pfno)) {
                alert("PF Number should only contain numbers.");
                return false;
            }
            
            return true;
        }

        // Toggle password visibility
        function togglePasswordVisibility() {
            const password = document.getElementById("password");
            const toggleIcon = document.getElementById("togglePassword");

            if (password.type === "password") {
                password.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
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
            top: 70%;
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
                    <h2 class="form-title">Librarian Login</h2>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" onsubmit="return validateForm()">
                        <div class="mb-3">
                            <label for="name" class="form-label">Librarian Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Librarian Name" required>
                        </div>

                        <div class="mb-3">
                            <label for="pfno" class="form-label">PF Number</label>
                            <input type="text" class="form-control" id="pfno" name="pfno" placeholder="Enter Librarian PF No" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Librarian Email" required>
                        </div>

                        <div class="mb-3 password-toggle">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            <i class="fas fa-eye" id="togglePassword" onclick="togglePasswordVisibility()"></i>
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
</body>
</html>