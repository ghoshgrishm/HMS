<?php
include("database.php");

if (isset($_GET['dept_id'])) {
    $dept_id = $_GET['dept_id'];

    // Get the department name
    $dept_sql = "SELECT dept_name FROM department WHERE department_id = ?";
    $dept_stmt = mysqli_prepare($conn, $dept_sql);
    mysqli_stmt_bind_param($dept_stmt, "i", $dept_id);
    mysqli_stmt_execute($dept_stmt);
    $dept_result = mysqli_stmt_get_result($dept_stmt);
    $dept_row = mysqli_fetch_assoc($dept_result);
    $dept_name = $dept_row['dept_name'];

    echo "<h2 style='text-align:center;'>Doctors in <span style='color:#007BFF;'>$dept_name</span> Department</h2>";

    $sql = "SELECT * FROM doctor WHERE department_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $dept_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo "<div style='display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;'>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div style='border: 1px solid #aaa; border-radius: 10px; padding: 15px; width: 220px; background-color: #fff7f7; box-shadow: 2px 2px 8px rgba(0,0,0,0.1);'>";
            echo "<strong>" . $row['doctor_name'] . "</strong><br>";
            echo "Specialty: " . $row['specialty'] . "<br>";
            echo "ID: " . $row['doctor_id'];
            echo "</div>";
        }
    } else {
        echo "<p>No doctors found in this department.</p>";
    }

    echo "</div>";
    echo "<br><div style='text-align:center;'><a href='department_list.php'>⬅️ Back to Departments</a></div>";

} else {
    echo "No department selected.";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="add_doctor.php" class="add-doctor-btn">Add New Doctor:</a>
</body>
</html>
