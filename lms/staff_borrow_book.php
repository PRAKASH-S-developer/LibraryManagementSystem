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

// Fetch staff details with error handling
$query1 = "SELECT * FROM staff_acc_create WHERE pfno='$pfno' AND email='$email'";
$result1 = mysqli_query($conn, $query1);

if (!$result1) {
    die("Error in query: " . mysqli_error($conn)); // Debugging
}

if (mysqli_num_rows($result1) == 0) {
    die("User not found in database.");
}

$r1 = mysqli_fetch_assoc($result1);
$staff_id = $r1["staff_id"];

// Handle logout request
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location:index.html");
    exit();
}

// Handle borrow request
if (isset($_GET['borrow'])) {
    $bid = $_GET['borrow'];

    $q2 = "SELECT * FROM temp_staffs_borrow_book_request WHERE pfno='$pfno' AND staff_id='$staff_id' AND bid='$bid'";
    $result2 = mysqli_query($conn, $q2);

    if (!$result2) {
        die("Error checking borrow request: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result2) > 0) {
        echo "<script>alert('Your Borrow request is already submitted & it is waiting for approval'); window.location.href='staff_home.php';</script>";
        exit();
    }

    $result3 = mysqli_query($conn, "SELECT * FROM add_books WHERE bid='$bid'");

    if (!$result3) {
        die("Error fetching book details: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result3) > 0) {
        $r3 = mysqli_fetch_assoc($result3);

        $accession_number = $r3["accession_number"];
        $title = $r3["title"];
        $book_keyword = $r3["book_keyword"];
        $author = $r3["author"];
        $book_image = $r3["book_image"];
        $copies = $r3["copies"];
        $publication = $r3["publication"];
        $publisher = $r3["publisher"];
        $isbn = $r3["isbn"];
        $availability = $r3["availability"];
        $price = $r3["price"];
        $cupboard_name = $r3["cupboard_name"];
        $shelve_name = $r3["shelve_name"];

        $q2 = "INSERT INTO temp_staffs_borrow_book_request (tid, staff_id, name, pfno, email, bid,
         accession_number, title, book_keyword, author, book_image, copies, publication,
          publisher, isbn, availability, price, cupboard_name, shelve_name ) VALUES (NULL, '$staff_id','$name','$pfno','$email', 
           '$bid', '$accession_number', '$title', '$book_keyword', '$author','$book_image',
          '$copies','$publication', '$publisher','$isbn','$availability','$price','$cupboard_name','$shelve_name')";

        $insert_result = mysqli_query($conn, $q2);

        if (!$insert_result) {
            die("Error inserting borrow request: " . mysqli_error($conn));
        }

        echo "<script>alert('$title Borrow Request Sent Successfully'); window.location.href='staff_home.php';</script>";
        exit();
    } else {
        echo "<script>alert('Book not found in database.');</script>";
    }
}

// Fetch all books with error handling
$query = "SELECT * FROM add_books WHERE availability>0";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching books: " . mysqli_error($conn));
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Borrow Books</title>
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


    <h2 class="text-center mb-4 text-warning">📚 Borrow Books</h2>

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
                <th>Available Copies</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="bookTable">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['bid']; ?></td>
                    <td><img src="<?php echo $row['book_image']; ?>" alt="Book Cover" width="80" height="100"></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['author']; ?></td>
                    <td><?php echo $row['copies']; ?></td>
                    <td><?php echo $row['publication']; ?></td>
                    <td><?php echo $row['publisher']; ?></td>
                    <td><?php echo $row['isbn']; ?></td>
                    <td><?php echo $row['availability']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['price']; ?></td>  
                    <td>
                        <a href="?borrow=<?php echo $row['bid']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to borrow this book?');">Borrow</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2024 Library Management System - By PRAKASH S</p>
</footer>
</body>
</html>
