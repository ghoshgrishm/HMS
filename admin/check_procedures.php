<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $sql = "SELECT p.patient_name, m.procedure_id, m.admission_id, m.procedure_type, m.observations, procedure_date
            FROM procedure_ipd m
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
    <title>Check procedures</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Check procedures</h1>
    
    <form method="post">
        <label for="patient_name">Enter Patient's Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $procedure_ipd): ?>
                <div class="procedure-record">
                    <strong>Patient's name:</strong> <?= htmlspecialchars($procedure_ipd['patient_name']) ?><br>
                    <strong>Procedure ID:</strong> <?= htmlspecialchars($procedure_ipd['procedure_id']) ?><br>
                    <strong>Admission ID:</strong> <?= htmlspecialchars($procedure_ipd['admission_id']) ?><br>
                    <strong>Procedure Type:</strong> <?= htmlspecialchars($procedure_ipd['procedure_type']) ?><br>
                    <strong>observations:</strong> <?= htmlspecialchars($procedure_ipd['observations']) ?><br>
                    <strong>Procedure date:</strong> <?= htmlspecialchars($procedure_ipd['procedure_date']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No procedure records found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_procedure.php" class="new-procedure-btn">Add new procedure:</a>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>