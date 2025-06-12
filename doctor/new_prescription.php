<?php
include("../database.php");

$patients = [];
$msg = $err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $name = trim($_POST["patient_name"]);
        if (empty($name)) {
            $err = "Please enter a name.";
        } else {
            $name = mysqli_real_escape_string($conn, $name);
            $sql = "SELECT * FROM patient WHERE LOWER(patient_name) LIKE LOWER('%$name%')";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $patients = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                $err = "No patients found with that name.";
            }
        }
    }

    if (isset($_POST['record_prescription'])) {
        $patient_id = $_POST['patient_id'] ?? '';
        $medicine_details = mysqli_real_escape_string($conn, $_POST['medicine_details'] ?? '');
        $dosage = mysqli_real_escape_string($conn, $_POST['dosage'] ?? '');
        $duration = mysqli_real_escape_string($conn, $_POST['duration'] ?? '');
        $charge = mysqli_real_escape_string($conn, $_POST['charge'] ?? '');

        $sql = "SELECT appointment_id FROM appointment WHERE patient_id = '$patient_id' ORDER BY appointment_date DESC LIMIT 1";
        $res = mysqli_query($conn, $sql);

        $file_path = null;

        if (isset($_FILES['prescription_file']) && $_FILES['prescription_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "../uploads/prescriptions/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_ext = strtolower(pathinfo($_FILES['prescription_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

            if (in_array($file_ext, $allowed)) {
                $new_filename = "presc_" . time() . "_" . rand(1000,9999) . "." . $file_ext;
                $target_file = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['prescription_file']['tmp_name'], $target_file)) {
                    $file_path = $target_file;
                } else {
                    $err = "Failed to upload file.";
                }
            } else {
                $err = "Invalid file type.";
            }
        }

        if ($res && mysqli_num_rows($res) > 0 && !$err) {
            $appointment_id = mysqli_fetch_assoc($res)['appointment_id'];

            $insert = "INSERT INTO prescription (appointment_id, medicine_details, dosage, duration, charge, file_path)
                       VALUES ('$appointment_id', '$medicine_details', '$dosage', '$duration', '$charge', " . ($file_path ? "'$file_path'" : "NULL") . ")";

            if (mysqli_query($conn, $insert)) {
                $msg = "Prescription added successfully.";
            } else {
                $err = "Error inserting prescription.";
            }
        } elseif (!$err) {
            $err = "No appointment found for selected patient.";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add prescription</title>
    <style>
        .card {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 8px 0;
            cursor: pointer;
            border-radius: 6px;
            background-color: #f9f9f9;
        }
        .card:hover {
            background-color: #e6f7ff;
        }
        .hidden {
            display: none;
        }
        .btn {
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .error { color: red; }
        .success { color: green; }
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
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<h1>Record prescription for OPD Patient</h1>

<?php if ($err): ?><p class="error-message"><?= $err ?></p><?php endif; ?>
<?php if ($msg): ?><p class="success-message"><?= $msg ?></p><?php endif; ?>

<form method="post">
    Enter Patient Name:<br>
    <input type="text" name="patient_name" required><br>
    <button type="submit" name="search" class="btn">Search</button>
</form>

<?php if (!empty($patients)): ?>
    <h3>Click a patient to continue:</h3>
    <?php foreach ($patients as $p): ?>
        <div class="card" onclick="selectPatient(<?= htmlspecialchars(json_encode($p)) ?>)">
            <strong>ID:</strong> <?= $p['patient_id'] ?> |
            <strong>Name:</strong> <?= $p['patient_name'] ?> |
            <strong>Age:</strong> <?= $p['age'] ?> |
            <strong>DOB:</strong> <?= $p['dob'] ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div id="prescription-form" class="hidden">
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="patient_id" id="patient_id">
        <p><strong>Selected Patient ID:</strong> <span id="pid_display"></span></p>
        Medicine Details: <input type="text" name="medicine_details" required><br>
        Dosage: <input type="text" name="dosage" required><br>
        Duration (YYYY-MM-DD): <input type="date" name="duration" required><br>
        Charge in INR: <input type="number" name="charge" step="0.01" required><br>
        <label>Attach Prescription File (PDF/Image):</label>
        <input type="file" name="prescription_file" accept=".pdf,.jpg,.jpeg,.png"><br>
        <button type="submit" name="record_prescription" class="btn">Record prescription</button>
    </form>
</div>

<script>
function selectPatient(patient) {
    document.getElementById('prescription-form').classList.remove('hidden');
    document.getElementById('patient_id').value = patient.patient_id;
    document.getElementById('pid_display').innerText = patient.patient_id;
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}
</script>

<br>
<a href="home_doctor.php" class="go-home-btn">Go home</a>
</body>
</html>
