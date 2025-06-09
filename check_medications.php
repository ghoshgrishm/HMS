<?php
include("database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $sql = "SELECT p.patient_name, m.medication_id, m.admission_id, m.medicine_details, m.dosage, m.duration, m.charge
            FROM medication m
            JOIN admission a ON m.admission_id = a.admission_id
            JOIN patient p ON a.patient_id = p.patient_id
            WHERE p.patient_name LIKE '%$name%'";
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .medication-record {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .search-btn, .add-medication-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .add-medication-btn {
            background-color: #2196F3;
            margin-top: 10px;
        }
        .search-btn:hover, .add-medication-btn:hover {
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
    <h1>Check Medications</h1>
    
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
                    <strong>Charge:</strong> <?= htmlspecialchars($medication['charge']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No patients found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_medication.php" class="add-medication-btn">Add new medication:</a>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>