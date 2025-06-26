<?php
session_start();
include("../database.php");

$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : null;
$selected_department = isset($_GET['department_id']) ? intval($_GET['department_id']) : null;
$selected_doctor = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : null;

// If no patient_id provided, redirect to visited.php
if (!$patient_id) {
    header("Location: visited.php");
    exit;
}

// Step 4: Handle Submission (placed before any HTML output)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    $slot_id = intval($_POST['slot_id']);
    $doctor_id = intval($_POST['doctor_id']);
    $patient_id = intval($_POST['patient_id']);

    $insert = mysqli_query($conn, "INSERT INTO request (patient_id, doctor_id, slot_id, status) 
                                   VALUES ($patient_id, $doctor_id, $slot_id, 'pending')");

    if ($insert) {
        echo "<script>alert('Appointment request submitted successfully!'); 
              window.location.href='check_requests.php?patient_id=$patient_id';</script>";
        exit;
    } else {
        echo "<p style='color:red;'>Error submitting request: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Appointment Request</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #003366;
            margin: 20px;
            line-height: 1.6;
            min-height: 100vh;
            flex-direction: column;
        }
        h1 {
            font-size: 2.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            color: #003366;
            text-shadow: 0 2px 4px rgba(0, 51, 102, 0.1);
            position: relative;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #003366, #0066cc);
            border-radius: 2px;
        }
        h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #003366;
            padding-left: 1rem;
            width: 100%;
            max-width: 600px;
            align-self: center;
        }
        .box {
            display: inline-block;
            padding: 10px 20px;
            margin: 8px;
            border: 2px solid #007BFF;
            border-radius: 8px;
            cursor: pointer;
            background-color: white;
            color: #007BFF;
            text-align: center;
        }
        .box:hover {
            background-color: #007BFF;
            color: white;
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
            margin-top: 100px;
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
    <h1>Request Appointment</h1>

    <!-- Step 1: Select Department -->
    <?php if (!$selected_department && !$selected_doctor): ?>
        <h2>Select Department</h2>
        <?php
        $dept_result = mysqli_query($conn, "SELECT department_id, dept_name FROM department");
        while ($dept = mysqli_fetch_assoc($dept_result)) {
            echo "<a href='new_request.php?patient_id=$patient_id&department_id={$dept['department_id']}' class='box'>{$dept['dept_name']}</a>";
        }
        ?>

    <!-- Step 2: Select Doctor -->
    <?php elseif ($selected_department && !$selected_doctor): ?>
        <h3>Select Doctor</h3>
        <?php
        $doc_result = mysqli_query($conn, "SELECT doctor_id, doctor_name FROM doctor WHERE department_id = $selected_department");
        while ($doc = mysqli_fetch_assoc($doc_result)) {
            echo "<a href='new_request.php?patient_id=$patient_id&department_id=$selected_department&doctor_id={$doc['doctor_id']}' class='box'>{$doc['doctor_name']}</a>";
        }
        echo "<br><br><a href='new_request.php?patient_id=$patient_id' class='back-btn'style='color:red;'>← Back to Departments</a>";
        ?>

    <!-- Step 3: Select Slot -->
    <?php elseif ($selected_doctor): ?>
        <h3>Select Slot</h3>
        <?php
        $slot_result = mysqli_query($conn, "SELECT * FROM slot WHERE doctor_id = $selected_doctor AND status = 'available'");
        $has_slots = false;
        while ($slot = mysqli_fetch_assoc($slot_result)) {
            $has_slots = true;
            $slot_label = "{$slot['date']} {$slot['start_time']} - {$slot['end_time']}";
            echo "
                <form method='post' style='display:inline-block;'>
                    <input type='hidden' name='slot_id' value='{$slot['slot_id']}'>
                    <input type='hidden' name='doctor_id' value='$selected_doctor'>
                    <input type='hidden' name='patient_id' value='$patient_id'>
                    <input type='submit' name='submit_request' class='box' value='$slot_label'>
                </form>
            ";
        }
        if (!$has_slots) echo "<p>No available slots for this doctor.</p>";

        echo "<br><br><a href='new_request.php?patient_id=$patient_id&department_id=$selected_department' class='back-btn'style='color:red;'>← Back to Doctors</a>";
        ?>
    <?php endif; ?>

    <br><br>
    <a href="index_staff.php" class="go-home-btn">Go home</a>
</body>
</html>
