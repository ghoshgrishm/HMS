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

    if (isset($_POST['update_patient'])) {
        $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
        $id_submitted = mysqli_real_escape_string($conn, $_POST['id_submitted']);
        $arrive_w_escort = mysqli_real_escape_string($conn, $_POST['escort']);
        $escort_name = mysqli_real_escape_string($conn, $_POST['escort_name']);
        $relation_w_escort = mysqli_real_escape_string($conn, $_POST['relation']);
        $escort_number = mysqli_real_escape_string($conn, $_POST['escort_num']);
        $residential_add = mysqli_real_escape_string($conn, $_POST['address']);
        $medical_history = mysqli_real_escape_string($conn, $_POST['med_his']);

        $update = "UPDATE patient SET 
            id_submitted = '$id_submitted', 
            arrive_w_escort = '$arrive_w_escort', 
            escort_name = '$escort_name',
            relation_w_escort = '$relation_w_escort', 
            escort_number = '$escort_number', 
            residential_add = '$residential_add', 
            medical_history = '$medical_history', 
            first_visit = CURRENT_TIMESTAMP 
            WHERE patient_id = '$patient_id'";
        
        $res = mysqli_query($conn, $update);

        if ($res && mysqli_affected_rows($conn) > 0) {
            $msg = "Patient record updated successfully.";
        } else {
            $err = "No changes made or update failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update patient records</title>
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
        .error-message { color: red; }
        .success-message { color: green; }
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

<h1>Update records for existing Patients</h1>

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
        <div class="card" onclick='selectPatient(<?= json_encode($p) ?>)'>
            <strong>ID:</strong> <?= $p['patient_id'] ?> |
            <strong>Name:</strong> <?= $p['patient_name'] ?> |
            <strong>Age:</strong> <?= $p['age'] ?> |
            <strong>DOB:</strong> <?= $p['dob'] ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Step 3: Hidden procedure form -->
<div id="patient-form" class="hidden">
    <form method="post">
        <input type="hidden" name="patient_id" id="patient_id">
        <p><strong>Selected Patient ID:</strong> <span id="pid_display"></span></p>
        Identification proof submitted by the patient:<br>
        <input type="text" name="id_submitted" required><br>
        Any escort with the patient? (Leave escort details blank if none):<br>
        <input type="radio" name="escort" value="1" required> Yes<br>
        <input type="radio" name="escort" value="0"> No<br>
        Escort's name:<br>
        <input type="text" name="escort_name"><br>
        Escort's relation with the patient (or vice versa):<br>
        <input type="text" name="relation"><br>
        Escort's number:<br>
        <input type="number" name="escort_num"><br>
        Patient's residential address:<br>
        <input type="text" name="address"><br>
        Any significant medical history of the patient?<br>
        <input type="text" name="med_his"><br>
        <button type="submit" name="update_patient" class="btn">Update record</button>
    </form>
</div>

<script>
function selectPatient(patient) {
    document.getElementById('patient-form').classList.remove('hidden');
    document.getElementById('patient_id').value = patient.patient_id;
    document.getElementById('pid_display').innerText = patient.patient_id;
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}
</script>

<br>
<a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
