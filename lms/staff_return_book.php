<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION["name"]) || !isset($_SESSION["pfno"]) || !isset($_SESSION["email"])) {
    header("location: staff_login.php");
    exit();
}

$name = $_SESSION["name"];
$pfno = $_SESSION["pfno"];
$email = $_SESSION["email"];

// Fetch staff details
$result1 = mysqli_query($conn, "SELECT * FROM staff_acc_create WHERE pfno='$pfno' AND email='$email'");
if (!$result1 || mysqli_num_rows($result1) == 0) {
    die("User not found in database.");
}
$r1 = mysqli_fetch_assoc($result1);
$staff_id = $r1["staff_id"];

// Fetch Due Date details
$result10 = mysqli_query($conn, "SELECT * FROM issued_books_staffs WHERE pfno='$pfno' AND staff_id='$staff_id' LIMIT 1");
$r10 = mysqli_fetch_assoc($result10);
$return_date = $r10["return_date"] ?? '';
$staff_id = $r10["staff_id"] ?? '';
$name = $r10["name"] ?? '';
$pfno = $r10["pfno"] ?? '';
$email = $r10["email"] ?? '';
$bid = $r10["bid"] ?? '';
$accession_number = $r10["accession_number"] ?? '';
$title = $r10["title"] ?? '';
$book_keyword = $r10["book_keyword"] ?? '';
$author = $r10["author"] ?? '';
$book_image = $r10["book_image"] ?? '';
$copies = $r10["copies"] ?? '';
$publication = $r10["publication"] ?? '';
$publisher = $r10["publisher"] ?? '';
$isbn = $r10["isbn"] ?? '';
$availability = $r10["availability"] ?? '';
$price = $r10["price"] ?? '';
$cupboard_name = $r10["cupboard_name"] ?? '';
$shelve_name = $r10["shelve_name"] ?? '';
$issue_date = $r10["issue_date"] ?? '';
$return_date = $r10["return_date"] ?? '';

// Handle logout request
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location: index.html");
    exit();
}

// Fetch only books that are issued but not returned
$query = "SELECT * FROM issued_books_staffs WHERE pfno='$pfno' AND staff_id='$staff_id' AND bid NOT IN 
          (SELECT bid FROM returned_books_staffs WHERE pfno='$pfno' AND staff_id='$staff_id')";
$result = mysqli_query($conn, $query);

// Handle return request
if (isset($_GET['return'])) {
    $bid = $_GET['return'];
    
    // Check if return request is already submitted
    $checkRequest = "SELECT * FROM temp_staffs_return_book_request WHERE pfno='$pfno' AND staff_id='$staff_id' AND bid='$bid'";
    $resultCheck = mysqli_query($conn, $checkRequest);
    
    if (mysqli_num_rows($resultCheck) > 0) {
        echo "<script>alert('Your return request is already submitted & it is waiting for approval');</script>";
        echo '<meta http-equiv="refresh" content="1; url=staff_home.php"/>';
        exit();
    }
    
    $result3 = mysqli_query($conn, "SELECT * FROM add_books WHERE bid='$bid'");
    if ($result3 && mysqli_num_rows($result3) > 0) {
        $r3 = mysqli_fetch_assoc($result3);
        
        $q2 = "INSERT INTO temp_staffs_return_book_request (trid_staff, staff_id, name, pfno, email,  
        bid, accession_number, title, book_keyword, author, book_image, copies, 
        publication, publisher, isbn, availability, price, due_date, cupboard_name, shelve_name) 
               VALUES (NULL, '$staff_id', '$name', '$pfno', '$email', '$bid', '{$r3['accession_number']}', '{$r3['title']}', '{$r3['book_keyword']}', '{$r3['author']}', '{$r3['book_image']}', '{$r3['copies']}', '{$r3['publication']}', '{$r3['publisher']}', '{$r3['isbn']}', '{$r3['availability']}', '{$r3['price']}', '$return_date', '{$r3['cupboard_name']}', '{$r3['shelve_name']}')";
        
        if (mysqli_query($conn, $q2)) {
            echo "<script>alert('{$r3['title']} Return Request Sent Successfully');</script>";
            echo '<meta http-equiv="refresh" content="1; url=staff_home.php"/>';
        } else {
            echo "<script>alert('Error occurred while sending return request. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Book not found in database.');</script>";
    }
}

// Handle renew request
if (isset($_GET['renew']) && isset($_GET['staff_id'])) {
    $bid = $_GET['renew'];
    $staff_id = $_GET['staff_id'];

    // Fetch book details
    $book_query = "SELECT * FROM issued_books_staffs WHERE bid='$bid' AND staff_id='$staff_id'";
    $book_result = mysqli_query($conn, $book_query);

    if (!$book_result || mysqli_num_rows($book_result) == 0) {
        echo "<script>alert('Book or staff not found.'); window.location.href='staff_return_book.php';</script>";
        exit();
    }

    $checkrenewRequest = "SELECT * FROM temp_staffs_renew_book_request WHERE pfno='$pfno' AND staff_id='$staff_id' AND bid='$bid'";
    $renewresultCheck = mysqli_query($conn, $checkrenewRequest);
    
    if (mysqli_num_rows($renewresultCheck) > 0) {
        echo "<script>alert('Your renew request is already submitted & it is waiting for approval. Please Check Your E-Mail For Further Details.');</script>";
        echo '<meta http-equiv="refresh" content="1; url=staff_home.php"/>';
        exit();
    }

    $book_row = mysqli_fetch_assoc($book_result);
    $exp_return_date = $book_row["return_date"];

    // Calculate new return date by adding 90 days to the expected return date
    $new_return_date = date('Y-m-d', strtotime($exp_return_date . ' +90 days'));

    $insert_query2 = "INSERT INTO temp_staffs_renew_book_request (temp_renew_id, staff_id, name, pfno, email,  
    bid, accession_number, title, book_keyword, author, book_image, copies, publication, publisher,
     isbn, availability, price, cupboard_name, shelve_name, issued_date, due_date, new_return_date) 
           VALUES (NULL, '$staff_id', '$name', '$pfno', '$email', '$bid', '$accession_number', '$title', '$book_keyword', '$author',
           '$book_image', '$copies', '$publication', '$publisher', '$isbn', '$availability', '$price', '$cupboard_name',
           '$shelve_name', '$issue_date', '$return_date', '$new_return_date')";
    if (mysqli_query($conn, $insert_query2)) {
        echo "<script>alert('Renewal Request sent successfully!'); window.location.href='staff_home.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error Renewing book: " . mysqli_error($conn) . "'); window.location.href='staff_home.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Return Or Renew Books</title>
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
    <h2 class="text-center mb-4 text-warning">📚 Return Or Renew Books</h2>
    <input type="text" id="search" class="search-bar" placeholder="Search by any field...">
    
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Book Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Issued Date</th>
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
                    <td><?php echo $row['issue_date']; ?></td>
                    <td><?php echo $row['return_date']; ?></td>
                    <td>
                        <a href="?return=<?php echo $row['bid']; ?>" class="btn btn-danger">Return</a><br><br>
                        <a href="?renew=<?php echo $row['bid']; ?>&staff_id=<?php echo $staff_id; ?>" class="btn btn-primary">Renew</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<footer><p>&copy; 2024 Library Management System - By PRAKASH S</p></footer>
</body>
</html>