<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION["username"]) || !isset($_SESSION["rollno"]) || !isset($_SESSION["email"])) {
    header("location:student_login.php");
    exit();
}

$username = $_SESSION["username"];
$rollno = $_SESSION["rollno"];
$email = $_SESSION["email"];

// Fetch student details
$result1 = mysqli_query($conn, "SELECT * FROM st_acc_create WHERE rollno='$rollno' AND email='$email'");
if (!$result1) {
    die("Error fetching user details: " . mysqli_error($conn));
}
if (mysqli_num_rows($result1) == 0) {
    die("User not found in database.");
}
$r1 = mysqli_fetch_assoc($result1);
$stu_id = $r1["stu_id"];
$selectyourcourse = $r1["selectyourcourse"];
$selectyourdegree = $r1["selectyourdegree"];
$currectstudyingyear = $r1["currectstudyingyear"];

// Fetch Due Date details
$result10 = mysqli_query($conn, "SELECT * FROM issued_books WHERE rollno='$rollno' AND stu_id='$stu_id' LIMIT 1");
if (!$result10) {
    die("Error fetching due date: " . mysqli_error($conn));
}
$r10 = mysqli_fetch_assoc($result10);
$return_date = $r10["return_date"] ?? '';
$issued_date = $r10["issued_date"] ?? '';

$treturn_date = date('Y-m-d');
// Handle logout request
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location:index.html");
    exit();
}

// Fetch only books that are issued but not returned
$query = "SELECT * FROM issued_books WHERE rollno='$rollno' AND stu_id='$stu_id' AND bid NOT IN 
          (SELECT bid FROM returned_books WHERE rollno='$rollno' AND stu_id='$stu_id')";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error fetching issued books: " . mysqli_error($conn));
}

// Handle return request
if (isset($_GET['return'])) {
    $bid = $_GET['return'];
    
    // Check if return request is already submitted
    $checkRequest = "SELECT * FROM int_temp_return_book_request WHERE rollno='$rollno' AND stu_id='$stu_id' AND bid='$bid'";
    $resultCheck = mysqli_query($conn, $checkRequest);
    if (!$resultCheck) {
        die("Error checking return request: " . mysqli_error($conn));
    }
    
    if (mysqli_num_rows($resultCheck) > 0) {
        echo "<script>alert('Your return request is already submitted & it is waiting for approval');</script>";
        echo '<meta http-equiv="refresh" content="1; url=student_home.php"/>';
        exit();
    }
    
    $result3 = mysqli_query($conn, "SELECT * FROM add_books WHERE bid='$bid'");
    if (!$result3) {
        die("Error fetching book details: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($result3) > 0) {
        $r3 = mysqli_fetch_assoc($result3);
        
        $q2 = "INSERT INTO int_temp_return_book_request (ini_temp_id, stu_id, username, rollno, email, selectyourdegree, selectyourcourse,currectstudyingyear, bid, accession_number, title, book_keyword, author, book_image, copies, publication, publisher, isbn, availability, price, due_date, cupboard_name, shelve_name,issued_date,return_date) 
               VALUES (NULL, '$stu_id', '$username', '$rollno', '$email', '$selectyourdegree', '$selectyourcourse','$currectstudyingyear', '$bid', '{$r3['accession_number']}', '{$r3['title']}', '{$r3['book_keyword']}', '{$r3['author']}', '{$r3['book_image']}', '{$r3['copies']}', '{$r3['publication']}', '{$r3['publisher']}', '{$r3['isbn']}', '{$r3['availability']}', '{$r3['price']}', '$return_date', '{$r3['cupboard_name']}', '{$r3['shelve_name']}','$issued_date','$treturn_date')";
        
        if (mysqli_query($conn, $q2)) {
            echo "<script>alert('{$r3['title']} Return Request Sent Successfully');</script>";
            echo '<meta http-equiv="refresh" content="1; url=student_home.php"/>';
        } else {
            echo "<script>alert('Error occurred while sending return request. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Book not found in database.');</script>";
    }
}
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
            <a href="student_home.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-warning">📚 Return Books</h2>
    <input type="text" id="search" class="search-bar" placeholder="Search by any field...">
    
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Book Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Borrowed Date</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="bookTable">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['bid']; ?></td>
                    <td><img src="<?php echo $row['book_image']; ?>" width="80" height="100"></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['author']; ?></td>
                    <td><?php echo $row['issued_date']; ?></td> 
                    <td><?php echo $row['return_date']; ?></td>
                    <td><a href="?return=<?php echo $row['bid']; ?>" class="btn btn-danger">Return</a>
                    <a href="student_renew_book.php?bid=<?php echo $row['bid']; ?>&stu_id=<?php echo $row['stu_id'];  ?>" class="btn  btn-primary">Renew</a>
                </td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<footer><p>&copy; 2024 Library Management System - By PRAKASH S</p></footer>
</body>
</html>