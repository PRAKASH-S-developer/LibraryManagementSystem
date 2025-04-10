<?php
// db_connection.php
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT dname FROM department";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Department</title>
</head>
<body>
    <form method="POST" action="">
        <label for="department">Choose a department:</label>
        <select name="department" id="department">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['dname'] . "'>" . $row['dname'] . "</option>";
                }
            } else {
                echo "<option value=''>No departments available</option>";
            }
            ?>
        </select>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_department = $_POST['department'];

    $sql = "INSERT INTO selected_departments (department_name) VALUES ('$selected_department')";

    if (mysqli_query($conn, $sql)) {
        echo "Department saved successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
