<?php
session_start();
include("../database.php");

if (!isset($_SESSION['username'])) {
    die("You must be logged in to view this page.");
}

$username = $_SESSION['username'];
$doctor_id = null;

// Get doctor_id from user table
$sql = "SELECT doctor_id FROM user WHERE username = '$username' AND user_type = 'doctor'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $doctor_id = $row['doctor_id'];
} else {
    die("Doctor ID not found or you are not a doctor.");
}

// Fetch slots
$slot_sql = "SELECT date, start_time, end_time, status FROM slot 
             WHERE doctor_id = '$doctor_id' 
             ORDER BY date, start_time";
$slot_result = mysqli_query($conn, $slot_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Your Slots</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f7fa;
            padding: 30px;
            text-align: center;
        }

        h1 {
            color: #003366;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 16px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #003366;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f4f6f9;
        }

        .no-slots {
            margin-top: 30px;
            color: #999;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <h1>Your Available Slots</h1>

    <?php if (mysqli_num_rows($slot_result) > 0): ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
            </tr>
            <?php while ($slot = mysqli_fetch_assoc($slot_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($slot['date']); ?></td>
                    <td><?php echo htmlspecialchars($slot['start_time']); ?></td>
                    <td><?php echo htmlspecialchars($slot['end_time']); ?></td>
                    <td><?php echo ucfirst($slot['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-slots">You haven't added any slots yet.</p>
    <?php endif; ?>
    <br>
    <a href="push_slots.php" class="new-patient-btn">Push slots for next week</a>
    <br>
    <a href="home_doctor.php" class="go-home-btn">Go home</a>
</body>
</html>
