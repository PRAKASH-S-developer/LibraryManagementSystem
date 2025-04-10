<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION["name"], $_SESSION["pfno"], $_SESSION["email"])) {
    header("Location: librarian_login.php");
    exit();
}

$sql1 = "SELECT cupboard_name, cupboard_id FROM cupboards";
$result1 = mysqli_query($conn, $sql1);

$sql2 = "SELECT shelve_name, shelve_id FROM shelves";
$result2 = mysqli_query($conn, $sql2);

$sql3 = "SELECT book_keyword, book_keyword_id FROM book_keywords";
$result3 = mysqli_query($conn, $sql3);


// Initialize feedback variables
$feedback_message = "";
$feedback_class = "";

// Fetch book details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM add_books WHERE bid='$id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $book = mysqli_fetch_assoc($result);
    } else {
        $feedback_message = "Book not found.";
        $feedback_class = "alert-danger";
    }
} else {
    header("Location: view_books.php");
    exit();
}

// Handle form submission
if (isset($_POST['update'])) {

    $selected_bkeyword = $_POST['bkeyword'];
    $selected_cupboard = $_POST['cupboard'];
    $selected_shelve = $_POST['shelve'];

    $title = $_POST['title'];
    $author = $_POST['author'];
    $copies = $_POST['copies'];
    $publication = $_POST['publication'];
    $publisher = $_POST['publisher'];
    $isbn = $_POST['isbn'];
    $date_received = $_POST['date_received'];
    $availability = $_POST['availability'];
    $description = $_POST['description'];
    $price = $_POST['price'];


    $book_image = $book['book_image']; // Default to current image
    if (isset($_FILES['book_image']['name']) && $_FILES['book_image']['name'] != "") {
        // Delete the old image
        if (file_exists($book_image)) {
            unlink($book_image);
        }

        // Upload the new image
        $filename = $_FILES["book_image"]["name"];
        $tempname = $_FILES["book_image"]["tmp_name"];
        $book_image = "book_images/" . $filename;
        move_uploaded_file($tempname, $book_image);
    }

    $update_query = "UPDATE add_books SET title='$title', book_keyword='$selected_bkeyword', author='$author', copies='$copies', publication='$publication', publisher='$publisher', isbn='$isbn', date_received='$date_received', availability='$availability', description='$description', price='$price', cupboard_name='$selected_cupboard', shelve_name='$selected_shelve', book_image='$book_image' WHERE bid='$id'";

    if (mysqli_query($conn, $update_query)) {
        $feedback_message = "Book updated successfully!";
        $feedback_class = "alert-success";
        header("Refresh: 3; url=view_all_books.php");
    } else {
        $feedback_message = "Error updating record: " . mysqli_error($conn);
        $feedback_class = "alert-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: rgb(84, 109, 135); }
        .navbar { background-color: #343a40; }
        .navbar-brand, .navbar a { color: #ffffff !important; }
         footer { background-color: black; color: yellow; text-align: center; padding: 10px 0; margin-top: 20px; }
        .form-container { max-width: 600px; margin: auto; }
        #preview { width: 100px; margin-top: 10px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="#">Library Management System</a>
        <a href="view_all_books.php" class="btn btn-secondary">Back to Books</a>
    </div>
</nav>

<div class="container mt-5 form-container">
    <h2 class="text-center text-warning">✏️ Edit Book</h2>

    <!-- Feedback Alert -->
    <?php if (!empty($feedback_message)): ?>
        <div class="alert <?php echo $feedback_class; ?> text-center" role="alert">
            <?php echo $feedback_message; ?>
        </div>
    <?php endif; ?>

    <div class="card p-4 bg-white">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Book Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo $book['title']; ?>" required>
            </div>
            <div class="mb-3">
            <label class="form-label">Select Book Keyword</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select name="bkeyword" id="bkeyword">
            <?php
            if (mysqli_num_rows($result3) > 0) {
                while($row3 = mysqli_fetch_assoc($result3)) {
                    echo "<option value='" . $row3['book_keyword'] . "'>" . $row3['book_keyword'] . "</option>";
                }
            } else {
                echo "<option value=''>No keywords available</option>";
            }
            ?>
        </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Author Name</label>
                <input type="text" name="author" class="form-control" value="<?php echo $book['author']; ?>" required>
            </div>

            <div class="mb-3">
            <label class="form-label">Upload Book Cover Image</label>
            <input type="file" name="book_image" class="form-control" onchange="previewImage(event)">
            <img id="preview" src="<?php echo $book['book_image']; ?>" alt="Book Cover">
        </div>
            <div class="mb-3">
                <label class="form-label">Book Copies</label>
                <input type="number" name="copies" class="form-control" value="<?php echo $book['copies']; ?>" required min="1">
            </div>
            <div class="mb-3">
                <label class="form-label">Book Publication</label>
                <input type="text" name="publication" class="form-control" value="<?php echo $book['publication']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Publisher Name</label>
                <input type="text" name="publisher" class="form-control" value="<?php echo $book['publisher']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" class="form-control" value="<?php echo $book['isbn']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date Received</label>
                <input type="date" name="date_received" class="form-control" value="<?php echo $book['date_received']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Availability</label>
                <input type="text" name="availability" class="form-control" value="<?php echo $book['availability']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"><?php echo $book['description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label> 
                <input type="number" name="price" class="form-control" value="<?php echo $book['price']; ?>" required min="0" step="0.01">
            </div>
            <div class="mb-3">
            <label class="form-label">Select a cupboard</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select name="cupboard" id="cupboard">
            <?php
            if (mysqli_num_rows($result1) > 0) {
                while($row1 = mysqli_fetch_assoc($result1)) {
                    echo "<option value='" . $row1['cupboard_name'] . "'>" . $row1['cupboard_name'] . "</option>";
                }
            } else {
                echo "<option value=''>No cupboard available</option>";
            }
            ?>
        </select>
            </div>
            <div class="mb-3">
            <label class="form-label">Select a shelve</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select name="shelve" id="shelve">
            <?php
            if (mysqli_num_rows($result2) > 0) {
                while($row2 = mysqli_fetch_assoc($result2)) {
                    echo "<option value='" . $row2['shelve_name'] . "'>" . $row2['shelve_name'] . "</option>";
                }
            } else {
                echo "<option value=''>No shelves available</option>";
            }
            ?>
        </select>
            </div>

        <button type="submit" name="update" class="btn btn-success w-100">Update Book</button>
    </form>
</div>

<footer>
    <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</body>
</html>
