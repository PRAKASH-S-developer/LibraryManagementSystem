<?php
session_start();
require('fpdf/fpdf.php'); // Include FPDF library
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
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

// Initialize search and date filter variables
$search = isset($_POST['search']) ? $_POST['search'] : '';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$filter_applied = false;

// Build the query to find books that are issued but not returned
$query = "SELECT ib.*, sac.rollno 
          FROM issued_books ib
          JOIN st_acc_create sac ON ib.stu_id = sac.stu_id
          WHERE NOT EXISTS (
              SELECT 1 FROM returned_books rb 
              WHERE rb.stu_id = ib.stu_id AND rb.bid = ib.bid
          )";

if (!empty($search)) {
    $query .= " AND (ib.accession_number LIKE '%$search%' OR 
                    ib.title LIKE '%$search%' OR 
                    ib.author LIKE '%$search%' OR 
                    ib.username LIKE '%$search%' OR 
                    sac.rollno LIKE '%$search%')";
    $filter_applied = true;
}

if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND ib.issued_date BETWEEN '$start_date' AND '$end_date'";
    $filter_applied = true;
}

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}

// Handle PDF generation
if (isset($_POST["generate_pdf"])) {
    // Use the same query for PDF generation
    $pdf_result = mysqli_query($conn, $query);

    // Create PDF in Landscape mode with A4 size
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Title
    $pdf->Cell(0, 10, 'Library Management System - Pending Return Requests', 0, 1, 'C');
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
    $header = array('S.No', 'Acc No', 'Title', 'Author', 'Student Name', 'Roll No', 'Degree', 'Course', 'Year', 'Issued Date', 'Due Date');
    $columnWidths = array(10, 15, 40, 30, 25, 15, 20, 20, 10, 20, 20);

    // Center-align headers
    foreach ($header as $index => $col) {
        $pdf->Cell($columnWidths[$index], 10, $col, 1, 0, 'C');
    }
    $pdf->Ln();

    // Table data
    $pdf->SetFont('Arial', '', 8);
    $serialNumber = 1;
    while ($row = mysqli_fetch_assoc($pdf_result)) {
        $data = array(
            $serialNumber++,
            $row['accession_number'],
            $row['title'],
            $row['author'],
            $row['username'],
            $row['rollno'],
            $row['selectyourdegree'],
            $row['selectyourcourse'],
            $row['currectstudyingyear'],
            $row['issued_date'],
            $row['return_date'] // Changed from due_date to return_date
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
    $pdf_filename = 'Pending_Return_Requests_' . date('Y-m-d');
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
    <title>Students Not Returned Books</title>
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
        table { 
            background-color: #fff; 
            border-radius: 8px; 
            width: 100%; 
        }
        th, td { 
            text-align: center; 
            vertical-align: middle; 
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
            <a href="librarian_book_return.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-warning">📚 Students Not Returned Books</h2>
    
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

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>S.No</th>
                <th>Book Cover</th>
                <th>Accession No</th>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Available Copies</th>
                <th>Student Name</th>
                <th>Roll No</th>
                <th>Degree</th>
                <th>Course</th>
                <th>Year</th>
                <th>Issued Date</th>
                <th>Due Date</th>
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
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                        <td><?php echo htmlspecialchars($row['availability']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['rollno']); ?></td>
                        <td><?php echo htmlspecialchars($row['selectyourdegree']); ?></td>
                        <td><?php echo htmlspecialchars($row['selectyourcourse']); ?></td>
                        <td><?php echo htmlspecialchars($row['currectstudyingyear']); ?></td>
                        <td><?php echo htmlspecialchars($row['issued_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['return_date']); ?></td> <!-- Changed from due_date to return_date -->
                    </tr>
                <?php endwhile;
            } else {
                echo '<tr><td colspan="14" class="no-results">No pending return requests found</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2024 Government Arts College, C.Mutlur Chidambaram, Library Management System - By PRAKASH S, M.Sc. Computer Science</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>