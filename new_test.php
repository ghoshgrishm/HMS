<?php
include("database.php");

$patients = [];
$selected_patient = null;
$msg = $err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $name = trim($_POST["name"]);
        if (empty($name)) {
            $err = "Please enter a name.";
        } else {
            $name = mysqli_real_escape_string($conn, $name);
            $sql = "SELECT * FROM patient WHERE name = '$name'";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $patients = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                $err = "No patients found with that name.";
            }
        }
    }

    if (isset($_POST['record_test'])) {
        $patient_id = $_POST['patient_id'];
        $test = mysqli_real_escape_string($conn, $_POST['test']);
        $results = mysqli_real_escape_string($conn, $_POST['results']);

        $sql = "SELECT admission_id FROM admission WHERE patient_id = '$patient_id' ORDER BY admission_date DESC LIMIT 1";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $admission_id = mysqli_fetch_assoc($res)['admission_id'];

            $insert = "INSERT INTO test (admission_id, test_type, results)
                       VALUES ('$admission_id', '$test', '$results')";

            if (mysqli_query($conn, $insert)) {
                $msg = "Test recorded successfully.";
            } else {
                $err = "Error inserting test.";
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
    <title>Record Test</title>
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
    </style>
</head>
<body>

<h2>Record Patient Test</h2>

<?php if ($err): ?><p class="error"><?= $err ?></p><?php endif; ?>
<?php if ($msg): ?><p class="success"><?= $msg ?></p><?php endif; ?>

<!-- Step 1: Enter name -->
<form method="post">
    Enter Patient Name: <input type="text" name="name" required>
    <button type="submit" name="search" class="btn">Search</button>
</form>

<!-- Step 2: Display matching patient cards -->
<?php if (!empty($patients)): ?>
    <h3>Click a patient to continue:</h3>
    <?php foreach ($patients as $p): ?>
        <div class="card" onclick="selectPatient(<?= htmlspecialchars(json_encode($p)) ?>)">
            <strong>ID:</strong> <?= $p['patient_id'] ?> |
            <strong>Name:</strong> <?= $p['name'] ?> |
            <strong>Age:</strong> <?= $p['age'] ?> |
            <strong>DOB:</strong> <?= $p['dob'] ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Step 3: Hidden test form -->
<div id="test-form" class="hidden">
    <form method="post">
        <input type="hidden" name="patient_id" id="patient_id">
        <p><strong>Selected Patient ID:</strong> <span id="pid_display"></span></p>
        Test Type: <input type="text" name="test" required><br>
        Result: <input type="text" name="results" required><br>
        <button type="submit" name="record_test" class="btn">Record Test</button>
    </form>
</div>

<script>
function selectPatient(patient) {
    document.getElementById('test-form').classList.remove('hidden');
    document.getElementById('patient_id').value = patient.patient_id;
    document.getElementById('pid_display').innerText = patient.patient_id;
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}
</script>

</body>
</html>
