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

    if (isset($_POST['record_discharge'])) {
        $patient_id = $_POST['patient_id'];
        $notes = mysqli_real_escape_string($conn, $_POST['notes']);
        $follow_up = mysqli_real_escape_string($conn, $_POST['follow_up']);

        $sql = "SELECT admission_id FROM admission WHERE patient_id = '$patient_id' ORDER BY admission_date DESC LIMIT 1";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $admission_id = mysqli_fetch_assoc($res)['admission_id'];

            $insert = "INSERT INTO discharge (admission_id, notes, discharge_date, follow_up)
                       VALUES ('$admission_id', '$notes', CURRENT_TIMESTAMP, '$follow_up')";

            if (mysqli_query($conn, $insert)) {
                // Now delete the admission record
                $delete = "UPDATE admission SET discharged = 'yes' WHERE admission_id = '$admission_id'";
                if (mysqli_query($conn, $delete)) {
                    $msg = "Patient discharged and admission record updated successfully.";
                } else {
                    $err = "Discharged, but failed to remove admission record.";
                }
            } else {
                $err = "Error inserting discharge.";
            }
        } else {
            $err = "No admission found for selected patient.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Discharge</title>
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
    </style>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<h1>Record Discharge for Admitted IPD Patient</h1>

<?php if ($err): ?><p class="error-message"><?= $err ?></p><?php endif; ?>
<?php if ($msg): ?><p class="success-message"><?= $msg ?></p><?php endif; ?>

<!-- Step 1: Enter name -->
<form method="post">
    Enter Patient Name:<br>
    <input type="text" name="patient_name" required><br>
    <button type="submit" name="search" class="btn">Search</button>
</form>

<!-- Step 2: Display matching patient cards -->
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

<!-- Step 3: Hidden discharge form -->
<div id="discharge-form" class="hidden">
    <form method="post">
        <input type="hidden" name="patient_id" id="patient_id">
        <p><strong>Selected Patient ID:</strong> <span id="pid_display"></span></p>
        Notes:<br>
        <input type="text" name="notes" required><br>
        Estimated follow-up date:<br>
        <input type="date" name="follow_up"><br>
        <button type="submit" name="record_discharge" class="btn">Discharge Patient</button>
    </form>
</div>

<script>
function selectPatient(patient) {
    document.getElementById('discharge-form').classList.remove('hidden');
    document.getElementById('patient_id').value = patient.patient_id;
    document.getElementById('pid_display').innerText = patient.patient_id;
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}
</script>

<br>
<a href="home_doctor.php" class="go-home-btn">Go home</a>
</body>
</html>
