<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;
$showNewAppointmentOption = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['patient_name'])) {
        $name = mysqli_real_escape_string($conn, $_POST['patient_name']);

        $sql = "SELECT a.appointment_id, p.patient_name AS patient_name, c.doctor_name, d.dept_name, a.appointment_date, a.token_number
                FROM appointment a
                JOIN patient p ON a.patient_id = p.patient_id
                JOIN doctor c ON a.doctor_id = c.doctor_id
                JOIN department d ON a.department_id = d.department_id
                WHERE LOWER(p.patient_name) LIKE LOWER('%$name%')";
                
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
        } else {
            $showNewAppointmentOption = true;
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
    <title>Appointment Check</title>
    <style>
        
    </style>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Check booked appointments</h1>
    
    <form method="post">
        <label for="patient_name">Enter Patient Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
    <h2>Search Results</h2>
    <?php if (!empty($searchResults)): ?>
        <?php foreach ($searchResults as $appointment): ?>
            <div class="appointment-record">
                <strong>Appointment ID:</strong> <?= htmlspecialchars($appointment['appointment_id']) ?><br>
                <strong>Patient Name:</strong> <?= htmlspecialchars($appointment['patient_name']) ?><br>
                <strong>Doctor Name:</strong> <?= htmlspecialchars($appointment['doctor_name']) ?><br>
                <strong>Department Name:</strong> <?= htmlspecialchars($appointment['dept_name']) ?><br>
                <strong>Date:</strong> <?= htmlspecialchars($appointment['appointment_date']) ?><br>
                <strong>Token Number:</strong> <?= htmlspecialchars($appointment['token_number']) ?><br>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="error-message">No appointments found for this patient.</p>
    <?php endif; ?>
<?php endif; ?>


    <br>
    <a href="new_appointment.php" class="new-appointment-btn">Book New Appointment:</a>

    <br>
    <a href="home_nurse.php" class="go-home-btn">Go home</a>
</body>
</html>