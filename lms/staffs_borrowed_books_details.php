<?php
session_start();
require('fpdf/fpdf.php'); // Include FPDF library
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the librarian is logged in
if (!isset($_SESSION["name"])) {
    header("location:librarian_login.php");
    exit();
}

// Handle logout request
if (isset($_POST["logout"])) {
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session
    header("location:index.html");
    exit();
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM issued_books_staffs WHERE staff_b_issu_id='$id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: staff_borrowed_books.php");
        exit();
    } else {
        die("Error deleting record: " . mysqli_error($conn));
    }
}

// Initialize search and date filter variables
$search = isset($_POST['search']) ? $_POST['search'] : '';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$filter_applied = false;

// Build the query based on search and date filters
$query = "SELECT * FROM issued_books_staffs WHERE 1=1";
if (!empty($search)) {
    $query .= " AND (accession_number LIKE '%$search%' OR title LIKE '%$search%' OR author LIKE '%$search%' OR name LIKE '%$search%' OR publication LIKE '%$search%' OR publisher LIKE '%$search%')";
    $filter_applied = true;
}
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND issue_date BETWEEN '$start_date' AND '$end_date'";
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

    // Add PDF generated date in the top right corner
    $pdf->SetXY(250, 10);
    $pdf->Cell(40, 10, 'Generated: ' . date('Y-m-d'), 0, 1, 'R');

    // Title
    $pdf->SetXY(0, 10);
    $pdf->Cell(0, 10, 'Library Management System - Staff Borrowed Books Report', 0, 1, 'C');
    
    // Add search criteria if specified
    if (!empty($search)) {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Search Criteria: ' . $search, 0, 1, 'C');
    }
    
    // Add date range if specified
    if (!empty($start_date) && !empty($end_date)) {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Date Range: ' . date('M d, Y', strtotime($start_date)) . ' to ' . date('M d, Y', strtotime($end_date)), 0, 1, 'C');
    }
    
    $pdf->Ln(5);

    // Table headers
    $pdf->SetFont('Arial', 'B', 8);
    $header = array('S.NO', 'Acc No', 'Title', 'Author', 'Staff Name', 'Issue Date', 'Return Date');
    $columnWidths = array(10, 15, 83, 80, 35, 25, 25);

    // Center-align headers
    foreach ($header as $index => $col) {
        $pdf->Cell($columnWidths[$index], 10, $col, 1, 0, 'C');
    }
    $pdf->Ln();

    // Table data with auto-incremented S.NO
    $pdf->SetFont('Arial', '', 9);
    $serialNumber = 1;
    while ($row = mysqli_fetch_assoc($pdf_result)) {
        $data = array(
            $serialNumber++,
            $row['accession_number'],
            $row['title'],
            $row['author'],
            $row['name'],
            $row['issue_date'],
            $row['return_date']
        );

        // Calculate the height required for each row
        $height = 10;
        foreach ($data as $index => $value) {
            $lines = ceil($pdf->GetStringWidth($value) / $columnWidths[$index]);
            $height = max($height, $lines * 10);
        }

        // Output each cell with dynamic height and center alignment
        foreach ($data as $index => $value) {
            $pdf->Cell($columnWidths[$index], $height, $value, 1, 0, 'C');
        }
        $pdf->Ln();
    }

    // Output PDF with current date in the filename
    $pdf_filename = 'Staff_Borrowed_Books_Report_' . date('Y-m-d');
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
    <title>Staff Borrowed Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { background-color: rgb(84, 109, 135); display: flex; flex-direction: column; min-height: 100vh; }
        .navbar { background-color: #343a40; }
        .navbar-brand, .navbar a { color: #ffffff !important; }
        .container { flex: 1; max-width: 95%; }
        table { background-color: #fff; border-radius: 8px; width: 100%; }
        th, td { text-align: center; vertical-align: middle; }
        footer { background-color: black; color: yellow; text-align: center; padding: 10px 0; margin-top: auto; }
        .search-bar {
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
            font-size: 16px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        }
        .no-results { 
            text-align: center; 
            padding: 20px; 
            background-color: #fff; 
            border-radius: 8px; 
            margin-top: 20px;
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
    <h2 class="text-center mb-4 text-warning">📚 Staff Borrowed Books</h2>
    
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

    <!-- Table to Display Staff Borrowed Books -->
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>S.NO</th>
                <th>Accession No</th>
                <th>Book Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Copies</th>
                <th>Publication</th>
                <th>Publisher</th>
                <th>Available Copies</th>
                <th>Staff Name</th>
                <th>Issued Date</th>
                <th>Expected Return</th>
                <!-- <th>Action</th> -->
            </tr>
        </thead>
        <tbody id="bookTable">
            <?php 
            $serialNumber = 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $serialNumber++; ?></td>
                        <td><?php echo htmlspecialchars($row['accession_number']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['book_image']); ?>" alt="Book Cover" width="80" height="100"></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo htmlspecialchars($row['copies']); ?></td>
                        <td><?php echo htmlspecialchars($row['publication']); ?></td>
                        <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                        <td><?php echo htmlspecialchars($row['availability']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['issue_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                        <!-- <td> -->
                            <!-- <a href="?delete=<?php //echo $row['staff_b_issu_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a> -->
                        <!-- </td> -->
                    </tr>
                <?php endwhile;
            } else {
                echo '<tr><td colspan="13" class="no-results">No borrowed books found matching your criteria</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
</footer>
</body>
</html>