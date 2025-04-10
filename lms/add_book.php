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

// Handle logout request
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}

// Initialize feedback variables
$feedback_message = '';
$feedback_class = '';

if (isset($_POST["submit"])) {
    $book_image_path = "";

    $selected_bkeyword = $_POST['bkeyword'];
    $selected_cupboard = $_POST['cupboard'];
    $selected_shelve = $_POST['shelve'];

    $sql1 = "SELECT * FROM cupboards WHERE cupboard_name='$selected_cupboard'";
    $result1 = mysqli_query($conn, $sql1);
    
    $cupboard_id = ($result1 && mysqli_num_rows($result1) > 0) ? mysqli_fetch_assoc($result1)["cupboard_id"] : null;

    $sql2 = "SELECT * FROM shelves WHERE shelve_name='$selected_shelve'";
    $result2 = mysqli_query($conn, $sql2);
    $shelve_id = ($result2 && mysqli_num_rows($result2) > 0) ? mysqli_fetch_assoc($result2)["shelve_id"] : null;

    $sql3 = "SELECT * FROM book_keywords WHERE book_keyword='$selected_bkeyword'";
    $result3 = mysqli_query($conn, $sql3);
    $book_keyword_id = ($result3 && mysqli_num_rows($result3) > 0) ? mysqli_fetch_assoc($result3)["book_keyword_id"] : null;

    // Corrected to fetch accession_number from the add_books table
    $result11 = $conn->query("SELECT accession_number FROM add_books ORDER BY accession_number DESC LIMIT 1");

    $accession_number = ($result11 && $result11->num_rows > 0) ? $result11->fetch_assoc()['accession_number'] + 1 : 1;

    $filename = $_FILES["book_image"]["name"];
    $tempname = $_FILES["book_image"]["tmp_name"];
    $book_image = "book_images/" . $filename;
    move_uploaded_file($tempname, $book_image);

    $title = $_POST["title"];
    $author = $_POST["author"];
    $copies = $_POST["copies"];
    $publication = $_POST["publication"];
    $publisher = $_POST["publisher"];
    $isbn = $_POST["isbn"];
    $date_received = $_POST["date_received"];
    $availability = $_POST["availability"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['error_message'])) {
            $feedback_message = $_POST['error_message']; // Assign JS validation error to PHP variable
            $feedback_class = 'alert-danger'; // Set feedback class to danger for errors
        }
    }

    if (!empty($title)  && !empty($author) && !empty($copies) && !empty($publication) && !empty($publisher) && !empty($isbn) && !empty($date_received) && !empty($availability) && !empty($description) && !empty($price) && !empty($cupboard_id) && !empty($shelve_id) && !empty($book_keyword_id) && !empty($selected_bkeyword)) {
        $q = "SELECT * FROM add_books WHERE isbn='$isbn'";
        $result = mysqli_query($conn, $q);

        if ($result && mysqli_num_rows($result) == 0) {
            $q = "INSERT INTO add_books (bid, accession_number, title, book_keyword, book_keyword_id, author, book_image, copies, publication, publisher, isbn, date_received, availability, description, price, cupboard_name, cupboard_id, shelve_name, shelve_id) 
                  VALUES (NULL, '$accession_number', '$title', '$selected_bkeyword', '$book_keyword_id', '$author', '$book_image', '$copies', '$publication', '$publisher', '$isbn', '$date_received', '$availability', '$description', '$price', '$selected_cupboard', '$cupboard_id', '$selected_shelve', '$shelve_id')";
            $result = mysqli_query($conn, $q);

            if ($result) {
                $feedback_message = "Book <strong>$title</strong> is added successfully!";
                $feedback_class = 'alert-success';
                header("Refresh: 3; url=librarian_home.php");
            } else {
                $feedback_message = "An error occurred while adding the book: " . mysqli_error($conn);
                $feedback_class = 'alert-danger';
            }
        } else {
            $feedback_message = "The Book <strong>$title</strong> already exists. Try a different ISBN.";
            $feedback_class = 'alert-warning';
            header("Refresh: 3; url=add_book.php");
        }
    } else {
        $feedback_message = "Please fill all the details correctly.";
        $feedback_class = 'alert-warning';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: rgb(84, 109, 135); }
        .navbar { background-color: #343a40; }
        .navbar-brand, .navbar a { color: #ffffff !important; }
        .container { max-width: 800px; }
        .card { border-radius: 12px; border: none; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }
        .form-label { font-weight: 600; color: #495057; }
        .form-control { border-radius: 8px; border: 1px solid #ced4da; }
        .form-control:focus { border-color: #007bff; box-shadow: 0px 0px 8px rgba(0, 123, 255, 0.25); }
        .btn-primary { background-color: #007bff; border: none; border-radius: 8px; transition: 0.3s; }
        .btn-primary:hover { background-color: #0056b3; }
        footer { background-color: black; color: yellow; text-align: center; padding: 10px 0; margin-top: 20px; }
        .step { display: none; }
        .step.active { display: block; }
        .step-navigation { display: flex; justify-content: space-between; margin-top: 20px; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="#">Library Management System</a>
        <form method="post" class="d-flex gap-2">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>&nbsp;&nbsp;&nbsp;
            <a href="librarian_home.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</nav>

<!-- Main Container -->
<div class="container mt-5">
    <h2 class="text-center mb-4 text-primary">📚 Add New Book</h2>

    <!-- Feedback Alert -->
    <?php if (!empty($feedback_message)): ?>
        <div class="alert <?php echo $feedback_class; ?> text-center" role="alert">
            <?php echo $feedback_message; ?>
        </div>
    <?php endif; ?>

    <div class="card p-4 bg-white">
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <!-- Step 1: Book Details -->
            <div class="step active" id="step1">
                <div class="mb-3">
                    <label class="form-label">Book Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Book Keyword</label>
                    <select name="bkeyword" id="bkeyword" class="form-control" required>
                        <option value="">Select a Keyword</option>
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
                    <input type="text" name="author" id="author" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Book Cover Image</label>
                    <input type="file" name="book_image" id="book_image" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Book Copies</label>
                    <input type="number" name="copies" id="copies" class="form-control" required min="1">
                </div>
                <div class="step-navigation">
                    <button type="button" class="btn btn-secondary" onclick="nextStep(2)">Next</button>
                </div>
            </div>

            <!-- Step 2: Publication Details -->
            <div class="step" id="step2">
                <div class="mb-3">
                    <label class="form-label">Publication Year</label>
                    <select name="publication" class="form-control" required>
                        <option value="">Select Year</option>
                        <script>
                            const select = document.querySelector("select[name='publication']");
                            const currentYear = new Date().getFullYear();
                            for (let year = currentYear; year >= 1900; year--) {
                                let option = document.createElement("option");
                                option.value = year;
                                option.textContent = year;
                                select.appendChild(option);
                            }
                        </script>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Publisher Name</label>
                    <input type="text" name="publisher" id="publisher" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" id="isbn" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date Received</label>
                    <input type="date" name="date_received" class="form-control" required>
                </div>
                <div class="step-navigation">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Previous</button>
                    <button type="button" class="btn btn-secondary" onclick="nextStep(3)">Next</button>
                </div>
            </div>

            <!-- Step 3: Additional Details -->
            <div class="step" id="step3">
                <div class="mb-3">
                    <label class="form-label">Availability</label>
                    <input type="number" name="availability" id="availability" class="form-control" required min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label> 
                    <input type="number" name="price" id="price" class="form-control" required min="0" step="0.01">
                </div>
                <div class="mb-3">
                    <label class="form-label">Select a cupboard</label>
                    <select name="cupboard" id="cupboard" class="form-control" required>
                        <option value="">Select a cupboard</option>
                        <?php
                        if (mysqli_num_rows($result1) > 0) {
                            while($row1 = mysqli_fetch_assoc($result1)) {
                                echo "<option value='" . $row1['cupboard_name'] . "'>" . $row1['cupboard_name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select a shelve</label>
                    <select name="shelve" id="shelve" class="form-control" required>
                        <option value="">Select a Shelve</option>
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
                <div class="step-navigation">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Previous</button>
                    <button type="submit" name="submit" class="btn btn-primary">Add Book</button>
                </div>
            </div>
            <input type="hidden" name="error_message" id="error_message">
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function validateForm() {
        let title = document.getElementById("title").value;
        let author = document.getElementById("author").value;
        let bookImage = document.getElementById("book_image").value;
        let copies = document.getElementById("copies").value;
        let publisher = document.getElementById("publisher").value;
        let isbn = document.getElementById("isbn").value;
        let availability = document.getElementById("availability").value;
        let price = document.getElementById("price").value;
        let errorMessage = "";

        // Regex for letters and spaces
        let lettersRegex = /^[a-zA-Z\s,.:]+$/;

        // Regex for positive numbers
        let positiveNumberRegex = /^\d+$/;

        // Regex for ISBN (10 or 13 digits)
        let isbnRegex = /^(?:\d{10}|\d{13})$/;

        // Regex for image file extensions
        let imageRegex = /\.(jpg|jpeg|png|gif)$/i;

        // Validate Book Title
        if (!lettersRegex.test(title)) {
            errorMessage = "Book Title must contain only letters, spaces, commas, and periods!";
        }

        // Validate Author Name
        else if (!lettersRegex.test(author)) {
            errorMessage = "Author Name must contain only letters, spaces, commas, and periods!";
        }

        // Validate Book Cover Image
        else if (!imageRegex.test(bookImage)) {
            errorMessage = "Book Cover Image must be a valid image file (jpg, jpeg, png, gif)!";
        }

        // Validate Book Copies
        else if (!positiveNumberRegex.test(copies)) {
            errorMessage = "Book Copies must be a positive number!";
        }

        // Validate Publisher Name
        else if (!lettersRegex.test(publisher)) {
            errorMessage = "Publisher Name must contain only letters and spaces!";
        }

        // Validate ISBN
        else if (!isbnRegex.test(isbn)) {
            errorMessage = "ISBN must be either 10 or 13 digits!";
        }

        // Validate Availability
        else if (!positiveNumberRegex.test(availability)) {
            errorMessage = "Availability must be a positive number!";
        }

        // Validate Price
        else if (!positiveNumberRegex.test(price)) {
            errorMessage = "Price must be a positive number!";
        }

        if (errorMessage) {
            document.getElementById("error_message").value = errorMessage;
            alert(errorMessage);
            return false;
        }
        return true;
    }

    function nextStep(step) {
        document.querySelectorAll('.step').forEach((stepElement) => {
            stepElement.classList.remove('active');
        });
        document.getElementById(`step${step}`).classList.add('active');
    }

    function prevStep(step) {
        document.querySelectorAll('.step').forEach((stepElement) => {
            stepElement.classList.remove('active');
        });
        document.getElementById(`step${step}`).classList.add('active');
    }
</script>
</body>
</html>