<?php
session_start();
include("../database.php");

$doctor_id = $_SESSION['doctor_id'] ?? null;

if (!$doctor_id) {
    echo "Doctor not logged in.";
    exit();
}

// Handle approve/reject actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    $req_sql = "SELECT * FROM request WHERE request_id = ?";
    $stmt = mysqli_prepare($conn, $req_sql);
    mysqli_stmt_bind_param($stmt, "i", $request_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $request = mysqli_fetch_assoc($result);

    if ($request) {
        $patient_id = $request['patient_id'];
        $slot_id = $request['slot_id'];

        // Get date from slot
        $slot_sql = "SELECT date FROM slot WHERE slot_id = ?";
        $stmt_slot = mysqli_prepare($conn, $slot_sql);
        mysqli_stmt_bind_param($stmt_slot, "i", $slot_id);
        mysqli_stmt_execute($stmt_slot);
        $slot_result = mysqli_stmt_get_result($stmt_slot);
        $slot_data = mysqli_fetch_assoc($slot_result);
        $appointment_date = $slot_data['date'] ?? null;

        if ($action === "approve") {
            $update_req = "UPDATE request SET status = 'booked' WHERE request_id = ?";
            $stmt1 = mysqli_prepare($conn, $update_req);
            mysqli_stmt_bind_param($stmt1, "i", $request_id);
            mysqli_stmt_execute($stmt1);

            $update_slot = "UPDATE slot SET status = 'booked' WHERE slot_id = ?";
            $stmt2 = mysqli_prepare($conn, $update_slot);
            mysqli_stmt_bind_param($stmt2, "i", $slot_id);
            mysqli_stmt_execute($stmt2);

            $token = "TKN" . time() . rand(10, 99);

            $insert_apt = "INSERT INTO appointment (patient_id, doctor_id, department_id, appointment_date, slot_id, Token_number)
                           VALUES (?, ?, ?, ?, ?, ?)";
            $dummy_department_id = 1; // Change this logic if needed
            $stmt3 = mysqli_prepare($conn, $insert_apt);
            mysqli_stmt_bind_param($stmt3, "iiisis", $patient_id, $doctor_id, $dummy_department_id, $appointment_date, $slot_id, $token);
            mysqli_stmt_execute($stmt3);

        } elseif ($action === "reject") {
            $update_req = "UPDATE request SET status = 'rejected' WHERE request_id = ?";
            $stmt4 = mysqli_prepare($conn, $update_req);
            mysqli_stmt_bind_param($stmt4, "i", $request_id);
            mysqli_stmt_execute($stmt4);
        }
    }
}

// Fetch requests and slot info
$sql = "SELECT r.request_id, r.patient_id, p.patient_name, s.date, s.start_time, s.end_time, r.slot_id
        FROM request r
        JOIN patient p ON r.patient_id = p.patient_id
        JOIN slot s ON r.slot_id = s.slot_id
        WHERE r.status = 'pending' AND r.doctor_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $doctor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Requests</title>
    
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        .btn {
            padding: 5px 12px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .approve { background-color: #4CAF50; color: white; }
        .reject { background-color: #f44336; color: white; }
    </style>
</head>
<body>
    <h1>Patient Requests</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Appointment Date</th>
                <th>Time Slot</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['start_time'] . " - " . $row['end_time']) ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                        <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                        <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No pending requests.</p>
    <?php endif; ?>

    <br>
    <a href="home_doctor.php" class="go-home-btn">Go home</a>
</body>
</html>
