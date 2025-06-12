<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $sql = "SELECT p.patient_name, d.discharge_id, d.admission_id, d.notes, d.discharge_date
            FROM discharge d
            JOIN admission a ON d.admission_id = a.admission_id
            JOIN patient p ON a.patient_id = p.patient_id
            WHERE LOWER(p.patient_name) LIKE LOWER('%$name%')";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Discharges</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Check discharge history</h1>
    
    <form method="post">
        <label for="patient_name">Enter Patient's Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $discharge): ?>
                <div class="discharge-record">
                    <strong>Patient's name:</strong> <?= htmlspecialchars($discharge['patient_name']) ?><br>
                    <strong>discharge ID:</strong> <?= htmlspecialchars($discharge['discharge_id']) ?><br>
                    <strong>Admission ID:</strong> <?= htmlspecialchars($discharge['admission_id']) ?><br>
                    <strong>Notes:</strong> <?= htmlspecialchars($discharge['notes']) ?><br>
                    <strong>Discharge date:</strong> <?= htmlspecialchars($discharge['discharge_date']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No discharge reports found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_discharge.php" class="new-discharge-btn">Add new discharge:</a>
    <br>
    <a href="home_doctor.php" class="go-home-btn">Go home</a>
</body>
</html>