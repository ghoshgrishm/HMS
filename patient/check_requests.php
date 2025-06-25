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

$has_pending = false;
foreach ($all_requests as $req) {
    if ($req['status'] === 'pending') {
        $has_pending = true;
        break;
    }
}
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
        .card {
            border: 2px solid #007BFF;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            background-color: #f9f9f9;
        }
        .card h3 {
            margin-top: 0;
        }
        .status {
            font-weight: bold;
            color: #007BFF;
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
            cursor: pointer;
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
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px;
            padding: 0.9rem 2rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin: 0.5rem 0.5rem 0.5rem 0;
            color: #ffffff;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }
        .btn:hover {
            background: linear-gradient(135deg, #002244, #0052a3);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 51, 102, 0.4);
        }
        @media (max-width: 768px) {
            .main-container { padding: 20px; margin: 10px; }
            h1 { font-size: 2rem; padding: 15px 25px; }
            .box { padding: 10px 15px; margin: 5px; min-width: 70px; }
        }
    </style>
</head>
<body>

    <h1>My Appointment Requests</h1>

    <?php if (empty($all_requests)): ?>
        <p>No appointment requests found.</p>

    <?php elseif (!$has_pending): ?>
        <p>No pending requests.</p>

    <?php else: ?>
        <?php foreach ($all_requests as $row): ?>
            <div class="card">
                <h3>Doctor: <?= $row['doctor_name'] ?></h3>
                <p>Date: <?= $row['date'] ?> <br>
                   Time: <?= $row['start_time'] ?> - <?= $row['end_time'] ?></p>
                <p>Status: <span class="status"><?= ucfirst($row['status']) ?></span></p>

                <?php if ($row['status'] === 'pending'): ?>
                    <form method="post">
                        <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                        <button type="submit" name="cancel_request" class="cancel-btn">Cancel Request</button>
                    </form>
                <?php elseif ($row['status'] === 'cancelled'): ?>
                    <p>Cancelled By: <?= ucfirst($row['cancelled_by']) ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<br>
<a href="new_request.php" class="btn">Make a new request</a>
</body>
</html>
