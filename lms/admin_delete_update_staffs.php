<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

// Check if user is logged in
if (!isset($_SESSION["name"])) {
    header("location:admin_login.php");
    exit();
}

$feedback_message = '';
$feedback_class = '';
$redirect = false; // Flag to control redirection

// Handle delete request
if (isset($_GET['delete'])) {
    $staff_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM staff_acc_create WHERE staff_id = $staff_id";
    if (mysqli_query($conn, $delete_query)) {
        $feedback_message = "Staff ID <strong>$staff_id</strong> has been deleted successfully!";
        $feedback_class = 'alert-success';
    } else {
        $feedback_message = "Failed to delete Staff ID <strong>$staff_id</strong>.";
        $feedback_class = 'alert-danger';
    }
    $redirect = true;
}

// Handle update request
if (isset($_POST['update'])) {
    $staff_id = intval($_POST['staff_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $pfno = mysqli_real_escape_string($conn, $_POST['pfno']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $update_query = "UPDATE staff_acc_create SET 
                     name = '$name', 
                     pfno = '$pfno', 
                     email = '$email' 
                     WHERE staff_id = $staff_id";

    if (mysqli_query($conn, $update_query)) {
        $feedback_message = "<strong>$name</strong> has been updated successfully!";
        $feedback_class = 'alert-success';
    } else {
        $feedback_message = "Failed to update staff <strong>$name</strong>.";
        $feedback_class = 'alert-danger';
    }
    $redirect = true;
}

// Fetch staff details from the database
$query = "SELECT * FROM staff_acc_create";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staffs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: aquamarine;
        }

        footer {
            background-color: black;
            color: yellow;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">GACC Library Management System</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_add_staffs_home.php">Back</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="logout.php">
                            <button type="submit" name="logout" class="btn btn-danger btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h2 class="text-center mb-4">Manage Staffs</h2>

        <?php if (!empty($feedback_message)): ?>
            <div class="alert <?php echo $feedback_class; ?> text-center" role="alert">
                <?php echo $feedback_message; ?>
            </div>
            <?php if ($redirect): ?>
                <script>
                    setTimeout(function() {
                        window.location.href = 'admin_add_staffs_home.php';
                    }, 2000); // Redirect after 2 seconds
                </script>
            <?php endif; ?>
        <?php endif; ?>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Staff Name</th>
                    <th>Pf No</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['staff_id']; ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline-block;">
                                <input type="hidden" name="staff_id" value="<?php echo $row['staff_id']; ?>">
                                <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" class="form-control">
                        </td>
                        <td>
                                <input type="text" name="pfno" value="<?php echo htmlspecialchars($row['pfno']); ?>" class="form-control">
                        </td>
                        <td>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="form-control">
                        </td>
                        <td>
                                <button type="submit" name="update" class="btn btn-success btn-sm">Save</button> &nbsp;&nbsp;&nbsp;&nbsp;
                            </form>
                            <a href="?delete=<?php echo $row['staff_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this staff?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br>
    <footer>
        <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc.Computer Science</p>
    </footer>
</body>
</html>