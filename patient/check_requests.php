<?php
include("../database.php");

$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : null;

if (!$patient_id) {
    echo "No patient ID provided.";
    exit;
}

// Handle cancellation
if (isset($_POST['cancel_request'])) {
    $request_id = intval($_POST['request_id']);
    $cancel_query = "UPDATE request 
                     SET status = 'cancelled', cancelled_by = 'patient' 
                     WHERE request_id = $request_id AND patient_id = $patient_id";
    mysqli_query($conn, $cancel_query);
    echo "<script>alert('Appointment cancelled successfully.'); window.location.href='check_requests.php?patient_id=$patient_id';</script>";
    exit;
}

// Fetch all requests
$query = "
    SELECT r.request_id, r.status, r.cancelled_by, 
           s.date, s.start_time, s.end_time,
           d.doctor_name
    FROM request r
    JOIN slot s ON r.slot_id = s.slot_id
    JOIN doctor d ON r.doctor_id = d.doctor_id
    WHERE r.patient_id = $patient_id
    ORDER BY s.date, s.start_time
";
$result = mysqli_query($conn, $query);
$all_requests = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Appointment Requests</title>
    <link rel="stylesheet" href="hospital.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f7fa;
        }

        h1 {
            color: #003366;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }

        .card {
            border: 2px solid #007BFF;
            border-radius: 10px;
            padding: 15px;
            margin: 10px auto;
            background-color: #f9f9f9;
            width: 90%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin-top: 0;
        }
        .status {
            font-weight: bold;
            color: #007BFF;
        }
        .success-msg {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
        .cancel-btn {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #ff4d4d;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(217, 44, 44, 0.3);
        }
        .cancel-btn:hover {
            background-color: #cc0000;
            transform: translateY(-2px);
        }
        .btn {
            background: linear-gradient(135deg, #003366, #0066cc);
            box-shadow: 0 4px 15px rgba(0, 51, 102, 0.3);
            color: white;
            padding: 0.9rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin: 20px auto;
            display: block;
            width: fit-content;
        }
        .btn:hover {
            background: linear-gradient(135deg, #002244, #0052a3);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 51, 102, 0.4);
        }
        .go-home-btn {
            background: linear-gradient(135deg, #6f42c1, #8a63d2);
            box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
            color: white;
            padding: 0.9rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin: 20px auto;
            display: block;
            width: fit-content;
        }
        .go-home-btn:hover {
            background: linear-gradient(135deg, #5a32a3, #7952b3);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(111, 66, 193, 0.4);
        }
    </style>
</head>
<body>

    <h1>My Appointment Requests</h1>

    <?php if (empty($all_requests)): ?>
        <p style="text-align: center;">No appointment requests found.</p>
    <?php else: ?>
        <?php foreach ($all_requests as $row): ?>
            <div class="card">
                <h3>Doctor: <?= htmlspecialchars($row['doctor_name']) ?></h3>
                <p>Date: <?= htmlspecialchars($row['date']) ?> <br>
                   Time: <?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></p>
                <p>Status: <span class="status"><?= ucfirst($row['status']) ?></span></p>

                <?php if ($row['status'] === 'pending'): ?>
                    <form method="post">
                        <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                        <button type="submit" name="cancel_request" class="cancel-btn">Cancel Request</button>
                    </form>
                <?php elseif ($row['status'] === 'cancelled'): ?>
                    <p>Cancelled By: <?= ucfirst($row['cancelled_by']) ?></p>
                <?php elseif ($row['status'] === 'booked'): ?>
                    <p class="success-msg">✅ Congrats, your appointment has been successfully booked!</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="new_request.php" class="btn">Make a new request</a>
    <a href="index_patient.php" class="go-home-btn">Go home</a>
</body>
</html>
