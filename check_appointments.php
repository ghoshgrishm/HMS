<?php
include("database.php");

$searchResults = [];
$searchPerformed = false;
$redirectToBooking = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        
        $sql = "SELECT a.appointment_id, p.name AS patient_name, c.doctor_name, d.departnment_name,  a.appointment_date, a.token_number
                FROM appointment a
                JOIN patient p ON a.patient_id = p.patient_id
                JOIN doctor c ON a.doctor_id = c.doctor_id
                JOIN department d ON a.department_id = d.department_id
                WHERE p.name LIKE '%$name%'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
        } else {
            $redirectToBooking = true;
        }
        $searchPerformed = true;
    }
}

if ($redirectToBooking) {
    header("Location: book_appointment.php?patient=".urlencode($_POST['name']));
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
        .search-btn, .add-patient-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .add-patient-btn {
            background-color: #2196F3;
            margin-top: 10px;
        }
        .search-btn:hover, .add-patient-btn:hover {
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
    <h1>Appointment Check</h1>
    
    <form method="post">
        <label for="patient_name">Enter Patient Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed && !$redirectToBooking): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $appointment): ?>
                <div class="appointment-record">
                    <strong>Appointment ID:</strong> <?= htmlspecialchars($appointment['appointment_id']) ?><br>
                    <strong>Patient Name:</strong> <?= htmlspecialchars($appointment['patient_name']) ?><br>
                    <strong>Doctor name:</strong> <?= htmlspecialchars($appointment['doctor_name']) ?><br>
                    <strong>Department name:</strong> <?= htmlspecialchars($appointment['department_name']) ?><br>
                    <strong>Date:</strong> <?= htmlspecialchars($appointment['date']) ?><br>
                    <strong>Token Number:</strong> <?= htmlspecialchars($appointment['token_no']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No appointments found for this patient.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_appointment.php" class="add-patient-btn">Book New Appointment:</a>

    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>