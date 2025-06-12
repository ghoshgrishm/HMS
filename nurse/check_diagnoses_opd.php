<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $sql = "SELECT p.patient_name, d.diagnosis_id, d.test_id, d.test_required, d.test_done, d.diagnosis_details, d.observations, d.refer_ipd, d.diagnosis_date, d.charge
            FROM diagnosis_opd d
            JOIN appointment a ON d.appointment_id = a.appointment_id
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
    <title>Check diagnoses for OPD</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Check diagnoses for OPD patients</h1>
    
    <form method="post">
        <label for="patient_name">Enter Patient's Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $diagnosis): ?>
                <div class="diagnosis-record">
                    <strong>Patient's name:</strong> <?= htmlspecialchars($diagnosis['patient_name']) ?><br>
                    <strong>Diagnosis ID:</strong> <?= htmlspecialchars($diagnosis['diagnosis_id']) ?><br>
                    <strong>Test ID:</strong> <?= htmlspecialchars($diagnosis['test_id']) ?><br>
                    <strong>Test Required:</strong> <?= htmlspecialchars($diagnosis['test_required']) ?><br>
                    <strong>Test done:</strong> <?= htmlspecialchars($diagnosis['test_done']) ?><br>
                    <strong>Diagnosis details:</strong> <?= htmlspecialchars($diagnosis['diagnosis_details']) ?><br>
                    <strong>Observations:</strong> <?= htmlspecialchars($diagnosis['observations']) ?><br>
                    <strong>Refer to IPD:</strong> <?= htmlspecialchars($diagnosis['refer_ipd']) ?><br>
                    <strong>Date of Diagnosis:</strong> <?= htmlspecialchars($diagnosis['diagnosis_date']) ?><br>
                    <strong>Charge: Rs. </strong> <?= htmlspecialchars($diagnosis['charge']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No diagnosis reports found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="home_nurse.php" class="go-home-btn">Go home</a>
</body>
</html>