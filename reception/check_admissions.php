<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;
$showNewAdmissionOption = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['patient_name'])) {
        $name = mysqli_real_escape_string($conn, $_POST['patient_name']);
        
        $sql = "SELECT a.admission_id, p.patient_name AS patient_name, d.dept_name AS department_name, b.bed_number, a.patient_condition, a.admission_date, a.predict_discharge, a.discharged
                FROM admission a
                JOIN patient p ON a.patient_id = p.patient_id
                JOIN department d ON a.department_id = d.department_id
                JOIN bed b ON a.bed_id = b.bed_id
                WHERE LOWER(p.patient_name) LIKE LOWER('%$name%')";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
        } else {
            $showNewAdmissionOption = true;
        }
        $searchPerformed = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Check</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Check admission history</h1>
    
    <form method="post">
        <label for="patient_name">Enter Patient Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $admission): ?>
                <div class="admission-record">
                    <strong>Admission ID:</strong> <?= htmlspecialchars($admission['admission_id']) ?><br>
                    <strong>Patient Name:</strong> <?= htmlspecialchars($admission['patient_name']) ?><br>
                    <strong>Patient's Condition:</strong> <?= htmlspecialchars($admission['patient_condition']) ?><br>
                    <strong>Department Name:</strong> <?= htmlspecialchars($admission['department_name']) ?><br>
                    <strong>Bed Number:</strong> <?= htmlspecialchars($admission['bed_number']) ?><br>
                    <strong>Admission Date:</strong> <?= htmlspecialchars($admission['admission_date']) ?><br>
                    <strong>Predicted Date of Discharge:</strong> <?= htmlspecialchars($admission['predict_discharge']) ?><br>
                    <strong>Patient discharged?:</strong> <?= htmlspecialchars($admission['discharged']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No admission records found for this patient.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_admission.php" class="new-admission-btn">Make a new Admission</a>

    <br>
    <a href="home_reception.php" class="go-home-btn">Go home</a>
</body>
</html>
