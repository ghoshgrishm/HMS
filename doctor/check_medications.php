<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $sql = "SELECT p.patient_name, m.medication_id, m.admission_id, m.medicine_details, m.dosage, m.duration, m.charge
            FROM medication m
            JOIN admission a ON m.admission_id = a.admission_id
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
    <title>Check Medications</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Find Medications provided to IPD patients</h1>
    
    <form method="post">
        <label for="patient_name">Enter Patient's Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $medication): ?>
                <div class="medication-record">
                    <strong>Patient's name:</strong> <?= htmlspecialchars($medication['patient_name']) ?><br>
                    <strong>Medication ID:</strong> <?= htmlspecialchars($medication['medication_id']) ?><br>
                    <strong>Admission ID:</strong> <?= htmlspecialchars($medication['admission_id']) ?><br>
                    <strong>Medicine details:</strong> <?= htmlspecialchars($medication['medicine_details']) ?><br>
                    <strong>Dosage:</strong> <?= htmlspecialchars($medication['dosage']) ?><br>
                    <strong>Duration:</strong> <?= htmlspecialchars($medication['duration']) ?><br>
                    <strong>Charge: Rs</strong> <?= htmlspecialchars($medication['charge']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No medication reports found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_medication.php" class="new-medication-btn">Add new medication:</a>
    <br>
    <a href="home_doctor.php" class="go-home-btn">Go home</a>
</body>
</html>