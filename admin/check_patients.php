<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $sql = "SELECT * FROM patient WHERE LOWER(patient_name) LIKE LOWER('%$name%')";
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
    <title>Patient Search</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Patient Search</h1>
    
    <form method="post">
        <label for="name">Enter Patient's Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $patient): ?>
                <div class="patient-record">
                    <strong>Patient ID:</strong> <?= htmlspecialchars($patient['patient_id']) ?><br>
                    <strong>Patient's name:</strong> <?= htmlspecialchars($patient['patient_name']) ?><br>
                    <strong>Age:</strong> <?= htmlspecialchars($patient['age']) ?><br>
                    <strong>Date of Birth:</strong> <?= htmlspecialchars($patient['dob']) ?><br>
                    <strong>Sex:</strong> <?= htmlspecialchars($patient['sex']) ?><br>
                    <strong>Contact number:</strong> <?= htmlspecialchars($patient['contact_no']) ?><br>
                    <strong>Email address:</strong> <?= htmlspecialchars($patient['email_id']) ?><br>
                    <strong>Identification proof given:</strong> <?= htmlspecialchars($patient['id_submitted']) ?><br>
                    <strong>Arrived with escort:</strong> <?= $patient['arrive_w_escort'] ? 'Yes' : 'No' ?><br>
                    <strong>Name of escort:</strong> <?= htmlspecialchars($patient['escort_name']) ?><br>
                    <strong>Relation with escort:</strong> <?= htmlspecialchars($patient['relation_w_escort']) ?><br>
                    <strong>Escort's number:</strong> <?= htmlspecialchars($patient['escort_number']) ?><br>
                    <strong>Residential address:</strong> <?= htmlspecialchars($patient['residential_add']) ?><br>
                    <strong>Medical History:</strong> <?= htmlspecialchars($patient['medical_history']) ?><br>
                    <strong>First Visit:</strong> <?= htmlspecialchars($patient['first_visit']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No patients found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Add New Patient Button -->
    <br>
    <a href="new_patient.php" class="new-patient-btn">Add New Patient:</a>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>