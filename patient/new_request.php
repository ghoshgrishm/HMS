<?php
include("../database.php");

$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : null;
$selected_department = isset($_GET['department_id']) ? intval($_GET['department_id']) : null;
$selected_doctor = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : null;

// If no patient_id provided, redirect to visited.php
if (!$patient_id) {
    header("Location: visited.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Appointment Request</title>
    <link rel="stylesheet" href="hospital.css">
    <style>
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
    </style>
</head>
<body>
    <h1>Request Appointment</h1>

    <!-- Step 1: Select Department -->
    <?php if (!$selected_department && !$selected_doctor): ?>
        <h3>Select Department</h3>
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
        echo "<br><br><a href='new_request.php?patient_id=$patient_id' style='color:red;'>← Back to Departments</a>";
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

        echo "<br><br><a href='new_request.php?patient_id=$patient_id&department_id=$selected_department' style='color:red;'>← Back to Doctors</a>";
        ?>

    <?php endif; ?>

    <!-- Step 4: Handle Submission -->
    <?php
    if (isset($_POST['submit_request'])) {
        $slot_id = intval($_POST['slot_id']);
        $doctor_id = intval($_POST['doctor_id']);
        $patient_id = intval($_POST['patient_id']);

        $insert = mysqli_query($conn, "INSERT INTO request (patient_id, doctor_id, slot_id, status) 
                                       VALUES ($patient_id, $doctor_id, $slot_id, 'pending')");

        if ($insert) {
            echo "<script>alert('Appointment request submitted successfully!'); window.location.href='new_request.php?patient_id=$patient_id';</script>";
        } else {
            echo "<p style='color:red;'>Error submitting request: " . mysqli_error($conn) . "</p>";
        }
    }
    ?>
</body>
</html>
