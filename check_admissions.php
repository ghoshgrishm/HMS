<?php
include("database.php");

$searchResults = [];
$searchPerformed = false;
$reidrectToNewAdmission = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        
        $sql = "SELECT * FROM admission WHERE admission_id IN (
                SELECT patient_id FROM patient WHERE name LIKE '%$name%'
            )";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
        } else {
            $reidrectToNewAdmission = true;
        }
        $searchPerformed = true;
    }
}

if ($reidrectToNewAdmission) {
    header("Location: new_admission.php?patient=".urlencode($_POST['name']));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .patient-record {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .search-btn, .new-admission-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .new-admission-btn {
            background-color: #2196F3;
            margin-top: 10px;
        }
        .search-btn:hover, .new-admission-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <h1>Appointment Check</h1>
    
    <form method="post">
        <label for="name">Enter Patient Name:</label><br>
        <input type="text" id="name" name="name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed && !$redirectToBooking): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $appointment): ?>
                <div class="patient-record">
                    <strong>Appointment ID:</strong> <?= htmlspecialchars($appointment['appointment_id']) ?><br>
                    <strong>Patient ID:</strong> <?= htmlspecialchars($appointment['patient_id']) ?><br>
                    <strong>Doctor ID:</strong> <?= htmlspecialchars($appointment['doctor_id']) ?><br>
                    <strong>Department ID:</strong> <?= htmlspecialchars($appointment['department_id']) ?><br>
                    <strong>Date:</strong> <?= htmlspecialchars($appointment['date']) ?><br>
                    <strong>Token Number:</strong> <?= htmlspecialchars($appointment['token_no']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No appointments found for this patient.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_admission.php" class="new-admission-btn">New Admission:</a>
</body>
</html>