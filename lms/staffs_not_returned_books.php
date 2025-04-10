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

// Build the query to find books that are issued but not returned by staff
$query = "SELECT ib.*, sac.pfno 
          FROM issued_books_staffs ib
          JOIN staff_acc_create sac ON ib.staff_id = sac.staff_id
          WHERE NOT EXISTS (
              SELECT 1 FROM returned_books_staffs rb 
              WHERE rb.staff_id = ib.staff_id AND rb.bid = ib.bid
          )";

if (!empty($search)) {
    $query .= " AND (ib.accession_number LIKE '%$search%' OR 
                    ib.title LIKE '%$search%' OR 
                    ib.author LIKE '%$search%' OR 
                    ib.name LIKE '%$search%' OR 
                    sac.pfno LIKE '%$search%')";
    $filter_applied = true;
}

if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND ib.issue_date BETWEEN '$start_date' AND '$end_date'";
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
    $pdf->Cell(0, 10, 'Library Management System - Staff Not Returned Books', 0, 1, 'C');
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
    $header = array('S.No', 'Acc No', 'Title', 'Author', 'Staff Name', 'PF No', 'Issue Date', 'Due Date');
    $columnWidths = array(10, 15, 50, 40, 30, 15, 20, 20);

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
            $row['name'],
            $row['pfno'],
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
    $pdf_filename = 'Staff_Not_Returned_Books_' . date('Y-m-d');
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
    <title>Staff Not Returned Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        .book-cover {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .badge-overdue {
            background-color: #dc3545;
            color: white;
        }
        .badge-due {
            background-color: #ffc107;
            color: #212529;
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
        <a class="navbar-brand" href="#">
            <i class="fas fa-book-open me-2"></i>Library Management System
        </a>
        <form method="post" class="d-flex">
            <a href="librarian_book_return_staff.php" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <button type="submit" name="logout" class="btn btn-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
        </form>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-warning">
        <i class="fas fa-exclamation-circle me-2"></i>Staff Not Returned Books
    </h2>
    
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
                <button type="submit" name="generate_pdf" class="btn btn-success w-100">
                    <i class="fas fa-file-pdf me-1"></i> Generate PDF
                </button>
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
                <th>Staff Name</th>
                <th>PF No</th>
                <th>Issued Date</th>
                <th>Due Date</th>
                <!-- <th>Actions</th> -->
            </tr>
        </thead>
        <tbody id="bookTable">
            <?php 
            $serialNumber = 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) : 
                    $due_date = new DateTime($row['return_date']);
                    $today = new DateTime();
                    $is_overdue = $today > $due_date;
                    ?>
                    <tr>
                        <td><?php echo $serialNumber++; ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['book_image']); ?>" alt="Book Cover" class="book-cover"></td>
                        <td><?php echo htmlspecialchars($row['accession_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                        <td><?php echo htmlspecialchars($row['availability']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['pfno']); ?></td>
                        <td><?php echo htmlspecialchars($row['issue_date']); ?></td>
                        <td>
                            <?php if ($is_overdue) : ?>
                                <span class="badge rounded-pill bg-danger text-white">
                                    <?php echo htmlspecialchars($row['return_date']); ?>
                                </span>
                            <?php else : ?>
                                <span class="badge rounded-pill bg-warning text-dark">
                                    <?php echo htmlspecialchars($row['return_date']); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <!-- <td>
                            <a href="return_book_staff.php?bid=<?php //echo $row['bid']; ?>&staff_id=<?php //echo $row['staff_id']; ?>" 
                               class="btn btn-sm btn-success">
                               <i class="fas fa-check me-1"></i> Accept
                            </a>
                            <a href="send_reminder_staff.php?staff_id=<?php //echo $row['staff_id']; ?>&bid=<?php //echo $row['bid']; ?>" 
                               class="btn btn-sm btn-warning mt-1">
                               <i class="fas fa-bell me-1"></i> Remind
                            </a>
                        </td> -->
                    </tr>
                <?php endwhile;
            } else {
                echo '<tr><td colspan="12" class="no-results">
                        <i class="fas fa-book-open fa-3x mb-3 text-muted"></i>
                        <h4>No overdue books found</h4>
                        <p class="text-muted">All staff books have been returned on time</p>
                      </td></tr>';
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