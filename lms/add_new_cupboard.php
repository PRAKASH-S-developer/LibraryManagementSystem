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
    $cupboard_name = $_POST["cupboard_name"];

    if (!empty($cupboard_name)) {
        $q = "SELECT * FROM cupboards WHERE cupboard_name='$cupboard_name'";
        $result = mysqli_query($conn, $q);

        if (mysqli_num_rows($result) == 0) {
            $q = "INSERT INTO cupboards (cupboard_id, cupboard_name) VALUES (NULL, '$cupboard_name')";
            $result = mysqli_query($conn, $q);

            if ($result) {
                $feedback_message = "<strong>$cupboard_name</strong> is added successfully!";
                $feedback_class = 'alert-success';
                header("Refresh: 2; url=add_cupboard_home.php");
            } else {
                $feedback_message = "An error occurred while adding the cupboard. Please try again.";
                $feedback_class = 'alert-danger';
            }
        } else {
            $feedback_message = "The <strong>$cupboard_name</strong> already exists. Try a new cupboard name.";
            $feedback_class = 'alert-warning';
        }
    } else {
        $feedback_message = "Please enter cupboard name.";
        $feedback_class = 'alert-warning';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelve Adding</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('cupboard2.jpg');
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
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand">GACC Library Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_cupboard_home.php">Back</a>
                    </li>
                    <li class="nav-item">
                        <!-- Logout button -->
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="logout" class="btn btn-link nav-link" style="text-decoration: none;">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<br><br><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="form-title">Enter Cupboard Details</h2>

                    <!-- Feedback Alert -->
                    <?php if (!empty($feedback_message)): ?>
                        <div class="alert <?php echo $feedback_class; ?> text-center" role="alert">
                            <?php echo $feedback_message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Enter Cupboard Name</label>
                            <input type="text" class="form-control" id="cupboard_name" name="cupboard_name" placeholder="(Example) Cupboard 1" required>
                        </div>

                        <button type="submit" class="btn btn-custom w-100" name="register">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br><br><br><br>
    <footer>
        <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
    </footer>
</body>
</html>
