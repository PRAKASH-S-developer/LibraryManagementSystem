<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION["name"]) || !isset($_SESSION["pfno"]) || !isset($_SESSION["email"])) {
    header("location:staff_login.php");
    exit();
}

$name = $_SESSION["name"];
$pfno = $_SESSION["pfno"];
$email = $_SESSION["email"];

// Fetch returned books details
$result10 = mysqli_query($conn, "SELECT returned_date FROM returned_books_staffs WHERE pfno='$pfno' AND email='$email'");

$returned_date = "You have not returned any books yet.";
if ($result10 && mysqli_num_rows($result10) > 0) {
    $r10 = mysqli_fetch_assoc($result10);
    $returned_date = $r10["returned_date"];
}

// Handle logout request
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location:index.html");
    exit();
}

// Fetch all books issued to the staffs
$query = "SELECT * FROM returned_books_staffs WHERE pfno='$pfno' AND email='$email'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { background-color: rgb(84, 109, 135); display: flex; flex-direction: column; min-height: 100vh; }
        .navbar { background-color: #343a40; }
        .navbar-brand, .navbar a { color: #ffffff !important; }
        .container { flex: 1; }
        table { background-color: #fff; border-radius: 8px; }
        th, td { text-align: center; vertical-align: middle; }
        footer { background-color: black; color: yellow; text-align: center; padding: 10px 0; margin-top: auto; }
        .search-bar {
            width: 100%;
            max-width: 400px;
            margin: 0 auto 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
            font-size: 16px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        }
    </style>
    <script>
        $(document).ready(function() {
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#bookTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="#">Library Management System</a>
        <form method="post">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>&nbsp;&nbsp;&nbsp;
            <a href="staff_home.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-warning">📚 Borrowed Books</h2>
    
    <input type="text" id="search" class="search-bar" placeholder="Search by any field...">
    
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Book Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Copies</th>
                <th>Publication</th>
                <th>Publisher</th>
                <th>ISBN</th>
                <th>Price</th>
                <th>Borrowed Date</th>
                <th>Returned Date</th>
            </tr>
        </thead>
        <tbody id="bookTable">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['return_id_staff']; ?></td>
                    <td><img src="<?php echo $row['book_image']; ?>" alt="Book Cover" width="80" height="100"></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['author']; ?></td>
                    <td><?php echo $row['copies']; ?></td>
                    <td><?php echo $row['publication']; ?></td>
                    <td><?php echo $row['publisher']; ?></td>
                    <td><?php echo $row['isbn']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['issued_date']; ?></td>
                    <td><?php echo $returned_date; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
