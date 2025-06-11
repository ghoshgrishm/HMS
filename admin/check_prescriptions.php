<?php
include("database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $sql = "SELECT p.patient_name, m.prescription_id, m.appointment_id, m.medicine_details, m.dosage, m.duration, m.charge
            FROM prescription m
            JOIN appointment a ON m.appointment_id = a.appointment_id
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
    <title>Check prescriptions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .prescription-record {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .search-btn, .add-prescription-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .add-prescription-btn {
            background-color: #2196F3;
            margin-top: 10px;
        }
        .search-btn:hover, .add-prescription-btn:hover {
            opacity: 0.9;
        }
        .go-home-btn {
            padding: 8px 15px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .go-home-btn:hover {
            opacity: 0.9;
        }

    </style>
</head>
<body>
    <h1>Check Prescriptions</h1>
    
    <form method="post">
        <label for="patient_name">Enter Patient's Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $prescription): ?>
                <div class="prescription-record">
                    <strong>Patient's name:</strong> <?= htmlspecialchars($prescription['patient_name']) ?><br>
                    <strong>Prescription ID:</strong> <?= htmlspecialchars($prescription['prescription_id']) ?><br>
                    <strong>Appointment ID:</strong> <?= htmlspecialchars($prescription['appointment_id']) ?><br>
                    <strong>Medicine details:</strong> <?= htmlspecialchars($prescription['medicine_details']) ?><br>
                    <strong>Dosage:</strong> <?= htmlspecialchars($prescription['dosage']) ?><br>
                    <strong>Duration:</strong> <?= htmlspecialchars($prescription['duration']) ?><br>
                    <strong>Charge:</strong> <?= htmlspecialchars($prescription['charge']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No prescription records found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_prescription.php" class="add-prescription-btn">Add new prescription:</a>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>