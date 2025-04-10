<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

// Initialize feedback variables
$feedback_message = '';
$feedback_class = '';

if (isset($_POST["register"])) {
    $name = $_POST["name"];
    $pfno = $_POST["pfno"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Server-side validation
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $feedback_message = "Librarian Name must contain only letters.";
        $feedback_class = 'alert-warning';
    } elseif (!preg_match("/^[0-9]+$/", $pfno)) {
        $feedback_message = "PF Number must contain only numbers.";
        $feedback_class = 'alert-warning';
    } elseif (!empty($name) && !empty($pfno) && !empty($email) && !empty($password)) {
        $q = "SELECT * FROM lib_acc_create WHERE pfno='$pfno'";
        $result = mysqli_query($conn, $q);

        if (mysqli_num_rows($result) == 0) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $q = "INSERT INTO lib_acc_create (lib_id, name, pfno, email, password) VALUES (NULL, '$name', '$pfno', '$email', '$hashed_password')";
            $result = mysqli_query($conn, $q);

            if ($result) {
                $feedback_message = "User <strong>$name</strong> is created successfully!";
                $feedback_class = 'alert-success';
                header("Refresh: 2; url=admin_home.php");
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
    <title>Librarian Account Creation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validateForm() {
            let name = document.getElementById("name").value;
            let pfno = document.getElementById("pfno").value;
            let namePattern = /^[a-zA-Z ]+$/;
            let pfnoPattern = /^[0-9]+$/;
            
            if (!namePattern.test(name)) {
                alert("Librarian Name must contain only letters.");
                return false;
            }
            if (!pfnoPattern.test(pfno)) {
                alert("PF Number must contain only numbers.");
                return false;
            }
            return true;
        }
    </script>
    <style>
        body {
            background-image: url('staffreg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
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

        footer {
            background-color: black;
            color: yellow;
            text-align: center;
            padding: 10px 0;
            margin-top: 50px;
            width: 100%;
            position: relative;
            left: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="form-title">Librarian Account Creation</h2>

                    <!-- Feedback Alert -->
                    <?php if (!empty($feedback_message)): ?>
                        <div class="alert <?php echo $feedback_class; ?> text-center" role="alert">
                            <?php echo $feedback_message; ?>
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

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                        </div>

                        <button type="submit" class="btn btn-custom w-100" name="register">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
    </footer>
</body>
</html>
