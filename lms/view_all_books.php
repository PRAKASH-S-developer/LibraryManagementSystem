<?php
session_start();
require('fpdf/fpdf.php'); // Include FPDF library
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION["name"], $_SESSION["pfno"], $_SESSION["email"])) {
    header("Location: librarian_login.php");
    exit();
}

// Handle logout request
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Fetch the book image path
    $query = "SELECT book_image FROM add_books WHERE bid='$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $imagePath = $row['book_image'];
    
    // Delete the book from the database
    $delete_query = "DELETE FROM add_books WHERE bid='$id'";
    if (mysqli_query($conn, $delete_query)) {
        // Delete the image file from the local folder
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    header("Location: view_all_books.php");
    exit();
}

// Initialize search and date filter variables
$search = isset($_POST['search']) ? $_POST['search'] : '';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$filter_applied = false;

// Build the query based on search and date filters
$query = "SELECT * FROM add_books WHERE 1=1";
if (!empty($search)) {
    $query .= " AND (accession_number LIKE '%$search%' OR title LIKE '%$search%' OR author LIKE '%$search%' OR book_keyword LIKE '%$search%')";
    $filter_applied = true;
}
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND date_received BETWEEN '$start_date' AND '$end_date'";
    $filter_applied = true;
}

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}

// Handle PDF generation
if (isset($_POST["generate_pdf"])) {
    // Use the same query for PDF generation that includes both search and date filters
    $pdf_result = mysqli_query($conn, $query);

    // Create PDF in Landscape mode with A4 size
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Title
    $pdf->Cell(0, 10, 'Library Management System - Book Inventory Report', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'Generated: ' . date('Y-m-d'), 0, 1, 'C');
    
    // Add search criteria if specified
    if (!empty($search)) {
        $pdf->Cell(0, 10, 'Search Criteria: ' . $search, 0, 1, 'C');
    }
    
    // Add date range if specified
    if (!empty($start_date) && !empty($end_date)) {
        $pdf->Cell(0, 10, 'Date Range: ' . date('M d, Y', strtotime($start_date)) . ' to ' . date('M d, Y', strtotime($end_date)), 0, 1, 'C');
    }
    
    $pdf->Ln(10);

    // Table headers
    $pdf->SetFont('Arial', 'B', 8);
    
    // Define column widths (total width = 280mm for landscape A4)
    $w = array(8, 12, 40, 30, 12, 20, 25, 20, 20, 15, 15, 15, 15);
    
    // Header
    $header = array('ID', 'Acc No', 'Title', 'Author', 'Copies', 'Publication', 'Publisher', 'ISBN', 'Date Rec.', 'Avail.', 'Cupboard', 'Shelve', 'Price');
    
    for($i=0; $i<count($header); $i++) {
        $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
    }
    $pdf->Ln();

    // Table data with auto-incremented ID
    $pdf->SetFont('Arial', '', 7);
    $serialNumber = 1; // Initialize serial number counter
    while ($row = mysqli_fetch_assoc($pdf_result)) {
        // Prepare data with proper formatting
        $data = array(
            $serialNumber++, // Auto-incremented ID
            $row['accession_number'],
            $row['title'],
            $row['author'],
            $row['copies'],
            $row['publication'],
            $row['publisher'],
            $row['isbn'],
            $row['date_received'],
            $row['availability'],
            $row['cupboard_name'],
            $row['shelve_name'],
            $row['price']
        );

        // Calculate the number of lines needed for each cell
        $nb = 0;
        for($i=0; $i<count($data); $i++) {
            // Get string width and calculate lines needed
            $lines = ceil($pdf->GetStringWidth($data[$i]) / $w[$i]);
            $nb = max($nb, $lines);
        }
        
        $h = 5 * $nb; // Calculate row height
        
        // Issue a page break first if needed
        if($pdf->GetY() + $h > 190) {
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 8);
            for($i=0; $i<count($header); $i++) {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
            }
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 7);
        }
        
        // Output cells with adjusted height
        for($i=0; $i<count($data); $i++) {
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            
            // Draw the border
            $pdf->Rect($x, $y, $w[$i], $h);
            
            // Print the text
            $pdf->MultiCell($w[$i], 5, $data[$i], 0, 'C');
            
            // Put the position to the right of the cell
            $pdf->SetXY($x + $w[$i], $y);
        }
        
        // Go to the next line
        $pdf->Ln($h);
    }

    // Output PDF with current date in the filename
    $pdf_filename = 'Book_Inventory_Report_' . date('Y-m-d');
    if (!empty($search)) {
        $pdf_filename .= '_' . substr(preg_replace('/[^A-Za-z0-9]/', '_', $search), 0, 20);
    }
    if (!empty($start_date) && !empty($end_date)) {
        $pdf_filename .= '_' . date('Y-m-d', strtotime($start_date)) . '_to_' . date('Y-m-d', strtotime($end_date));
    }
    $pdf->Output('D', $pdf_filename . '.pdf');
    exit();
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
        body {
            background-color: rgb(84, 109, 135);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .navbar a {
            color: #ffffff !important;
        }
        .container {
            flex: 1;
            max-width: 95%;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            background-color: #fff;
            border-radius: 8px;
            text-align: left;
            width: 100%;
            table-layout: auto;
        }
        th, td {
            padding: 10px;
            white-space: nowrap;
        }
        footer {
            background-color: black;
            color: yellow;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }
        .search-bar {
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
            font-size: 16px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        }
        .filter-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .filter-item {
            margin-right: 15px;
            display: inline-block;
        }
        .no-results { 
            text-align: center; 
            padding: 20px; 
            background-color: #fff; 
            border-radius: 8px; 
            margin-top: 20px;
        }
    </style>
    <script>
        $(document).ready(function() {
            // Search functionality
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#bookTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            // Reset form button
            $("#resetFilters").click(function() {
                $("input[type='text'], input[type='date']").val("");
                window.location.href = window.location.pathname;
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
            <a href="librarian_home.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-warning">📚 All Books</h2>
    
    <!-- Show filter info if any filters are active -->
    <?php if ($filter_applied) : ?>
        <div class="filter-info">
            <?php if (!empty($search)) : ?>
                <span class="filter-item"><strong>Search:</strong> <?php echo htmlspecialchars($search); ?></span>
            <?php endif; ?>
            <?php if (!empty($start_date) && !empty($end_date)) : ?>
                <span class="filter-item"><strong>Date Range:</strong> <?php echo date('M d, Y', strtotime($start_date)); ?> to <?php echo date('M d, Y', strtotime($end_date)); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Search and Date Filter Form -->
    <form method="post" action="" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="search" name="search" class="form-control search-bar" 
                       placeholder="Search by any field..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" 
                       value="<?php echo htmlspecialchars($start_date); ?>" placeholder="Start Date">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" 
                       value="<?php echo htmlspecialchars($end_date); ?>" placeholder="End Date">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-1">
                <button type="button" id="resetFilters" class="btn btn-secondary w-100">Reset</button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-2 offset-md-10">
                <button type="submit" name="generate_pdf" class="btn btn-success w-100">Generate PDF</button>
            </div>
        </div>
    </form>

    <div class="table-container">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Book Cover</th>
                    <th>Accession Number</th>
                    <th>Title</th>
                    <th>Book Keyword</th>
                    <th>Author</th>
                    <th>Copies</th>
                    <th>Publication</th>
                    <th>Publisher</th>
                    <th>ISBN</th>
                    <th>Date Received</th>
                    <th>Availability</th>
                    <th>Description</th>
                    <th>Cupboard Name</th>
                    <th>Shelve Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookTable">
                <?php 
                $serialNumber = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $serialNumber++; ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['book_image']); ?>" alt="Book Cover" width="80" height="100"></td>
                            <td><?php echo htmlspecialchars($row['accession_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_keyword']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo htmlspecialchars($row['copies']); ?></td>
                            <td><?php echo htmlspecialchars($row['publication']); ?></td>
                            <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                            <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_received']); ?></td>
                            <td><?php echo htmlspecialchars($row['availability']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['cupboard_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['shelve_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td>
                                <a href="edit_book.php?id=<?php echo $row['bid']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="?delete=<?php echo $row['bid']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile;
                } else {
                    echo '<tr><td colspan="17" class="no-results">No books found matching your criteria</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
</footer>
</body>
</html>