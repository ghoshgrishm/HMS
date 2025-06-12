<?php
include("../database.php");

$patients = [];
$beds = [];
$doctors = [];
$msg = $err = "";
$selected_patient_id = null;
$selected_department_id = null;

session_start(); // Required for using $_SESSION

// Fetch doctor list
$doc_sql = "SELECT doctor_id, doctor_name FROM doctor";
$doc_res = mysqli_query($conn, $doc_sql);
while ($d = mysqli_fetch_assoc($doc_res)) {
    $doctors[] = $d;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search_patient'])) {
        $name = trim($_POST["patient_name"]);
        if (!empty($name)) {
            $name = mysqli_real_escape_string($conn, $name);
            $sql = "SELECT patient_id, patient_name, age, dob, sex FROM patient WHERE patient_name LIKE '%$name%'";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $patients[] = $row;
            }
            if (empty($patients)) $err = "No patients found matching your entry.";
        } else {
            $err = "Please enter a name";
        }
    }

    if (isset($_POST['fetch_beds'])) {
        $selected_patient_id = $_POST['selected_patient_id'];
        $condition = mysqli_real_escape_string($conn, $_POST['patient_condition']);
        $discharge = mysqli_real_escape_string($conn, $_POST['discharge']);
        $dept = mysqli_real_escape_string($conn, $_POST['dept']);
        $charge = mysqli_real_escape_string($conn, $_POST['charge']);
        $doctor_name = mysqli_real_escape_string($conn, $_POST['doctor_name']);

        $sql_dept = "SELECT department_id FROM department WHERE dept_name = '$dept'";
        $result_dept = mysqli_query($conn, $sql_dept);
        if ($result_dept && mysqli_num_rows($result_dept) > 0) {
            $selected_department_id = mysqli_fetch_assoc($result_dept)['department_id'];
            $sql_beds = "SELECT bed_id, bed_number FROM bed WHERE department_id = '$selected_department_id' AND bed_status = 'available'";
            $result_beds = mysqli_query($conn, $sql_beds);
            while ($row = mysqli_fetch_assoc($result_beds)) {
                $beds[] = $row;
            }
        } else {
            $err = "Department not found.";
        }

        // Get doctor_id
        $sql_doc = "SELECT doctor_id FROM doctor WHERE LOWER(doctor_name) = LOWER('$doctor_name')";
        $result_doc = mysqli_query($conn, $sql_doc);
        $doctor_id = ($result_doc && mysqli_num_rows($result_doc) > 0) ? mysqli_fetch_assoc($result_doc)['doctor_id'] : null;

        $_SESSION = [
            'patient_id' => $selected_patient_id,
            'patient_condition' => $condition,
            'discharge' => $discharge,
            'department_id' => $selected_department_id,
            'charge' => $charge,
            'doctor_id' => $doctor_id
        ];
    }

    if (isset($_POST['admit_patient'])) {
        $bed_id = $_POST['bed_id'];
        $patient_id = $_SESSION['patient_id'];
        $condition = $_SESSION['patient_condition'];
        $discharge = $_SESSION['discharge'];
        $department_id = $_SESSION['department_id'];
        $charge = $_SESSION['charge'];
        $doctor_id = $_SESSION['doctor_id'];

        $insert_sql = "INSERT INTO admission (patient_id, patient_condition, department_id, bed_id, predict_discharge, charge, doctor_id)
                       VALUES ('$patient_id', '$condition', '$department_id', '$bed_id', '$discharge', '$charge', '$doctor_id')";
        if (mysqli_query($conn, $insert_sql)) {
            $update_bed = "UPDATE bed SET bed_status = 'occupied' WHERE bed_id = '$bed_id'";
            mysqli_query($conn, $update_bed);
            $msg = "Patient admitted successfully.";
        } else {
            $err = "Admission failed: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Admission</title>
    <style>
        .box { padding:10px; margin:5px; border:1px solid #ccc; display:inline-block; cursor:pointer; }
        .success-message { color: green; }
        .error-message { color: red; }
    </style>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Make a new Admission</h1>
    <?php if ($msg) echo "<p class='success-message'>$msg</p>"; ?>
    <?php if ($err) echo "<p class='error-message'>$err</p>"; ?>

    <!-- Step 1: Search patient -->
    <form method="post">
        Search patient by name:<br>
        <input type="text" name="patient_name" placeholder="Patient name" required>
        <button type="submit" name="search_patient">Search</button><br><br><br>
    </form>

    <?php if (!empty($patients)): ?>
        <h3>Click a patient to continue:</h3>
        <form method="post">
            <?php foreach ($patients as $p): ?>
                <button type="submit" name="selected_patient_id" value="<?= $p['patient_id'] ?>" class="card">
                    <strong>ID:</strong> <?= $p['patient_id'] ?><br>
                    <strong>Patient's Name:</strong> <?= htmlspecialchars($p['patient_name']) ?><br>
                    <strong>Age:</strong> <?= $p['age'] ?? 'N/A' ?><br>
                    <strong>DOB:</strong> <?= $p['dob'] ?? 'N/A' ?><br>
                    <strong>Sex:</strong> <?= $p['sex'] ?? 'N/A' ?><br>
                </button>
            <?php endforeach; ?>
        </form>
    <?php endif; ?>

    <!-- Step 2: Enter details and get beds -->
    <?php if (isset($_POST['selected_patient_id']) || $selected_patient_id): ?>
        <strong>Enter patient details below</strong><br><br>
        <form method="post">
            <input type="hidden" name="selected_patient_id" value="<?= $_POST['selected_patient_id'] ?? $selected_patient_id ?>">
            Patient Condition:<br>
            <input type="text" name="patient_condition" required><br>
            Department:<br>
            <input type="text" name="dept" required><br>
            Predicted discharge date:<br>
            <input type="date" name="discharge" required><br>
            Charge:<br>
            <input type="number" name="charge" step="0.01" required><br>
            Assigned Doctor:<br>
            <input type="text" name="doctor_name" required><br>
            <button type="submit" name="fetch_beds">Get Available Beds</button>
        </form>
    <?php endif; ?>

    <!-- Step 3: Show beds -->
    <?php if (!empty($beds)): ?>
        <h3>Select Bed:</h3>
        <form method="post">
            <?php foreach ($beds as $b): ?>
                <button class="box" type="submit" name="bed_id" value="<?= $b['bed_id'] ?>">
                    Bed <?= $b['bed_number'] ?>
                </button>
            <?php endforeach; ?>
            <input type="hidden" name="admit_patient" value="1">
        </form>
    <?php endif; ?>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
