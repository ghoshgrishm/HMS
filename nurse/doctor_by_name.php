<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_name = mysqli_real_escape_string($conn, $_POST["doctor_name"]);
    $sql = "SELECT doctor.*, department.dept_name 
            FROM doctor 
            LEFT JOIN department ON doctor.department_id = department.department_id
            WHERE LOWER(doctor.doctor_name) LIKE LOWER('%$doctor_name%')";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    }
    $searchPerformed = true;
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search by Name</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Search by Name</h1>
    
    <form method="post">
        <label for="name">Enter the Doctor's name:</label><br>
        <input type="text" id="doctor_name" name="doctor_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $doctor): ?>
                <div class="doctor-record">
                    <strong>Doctor ID:</strong> <?= htmlspecialchars($doctor['doctor_id']) ?><br>
                    <strong>Name:</strong> <?= htmlspecialchars($doctor['doctor_name']) ?><br>
                    <strong>Specialty:</strong> <?= htmlspecialchars($doctor['specialty']) ?><br>
                    <strong>Department:</strong> <?= htmlspecialchars($doctor['dept_name']) ?><br>
                    <br>
                    <a href="doctor_slots.php?doctor_id=<?= $doctor['doctor_id'] ?>" class="new-patient-btn">Check doctor slots</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No doctors found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="home_nurse.php" class="go-home-btn">Go home</a>
</body>
</html>
