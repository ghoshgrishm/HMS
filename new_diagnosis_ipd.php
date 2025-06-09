<?php
include("database.php");

$patients = [];
$msg = $err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $name = trim($_POST["patient_name"]);
        if (empty($name)) {
            $err = "Please enter a name.";
        } else {
            $name = mysqli_real_escape_string($conn, $name);
            $sql = "SELECT * FROM patient WHERE patient_name = '$name'";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $patients = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                $err = "No patients found with that name.";
            }
        }
    }

    if (isset($_POST['submit_diagnosis'])) {
        $patient_id = $_POST['patient_id'];
        $test_required = mysqli_real_escape_string($conn, $_POST['test_required']);
        $test_done = mysqli_real_escape_string($conn, $_POST['test_done']);
        $diagnosis_details = mysqli_real_escape_string($conn, $_POST['diagnosis_details']);
        $observations = mysqli_real_escape_string($conn, $_POST['observations']);
        $patient_condition = mysqli_real_escape_string($conn, $_POST['patient_condition']);
        $decision = mysqli_real_escape_string($conn, $_POST['decision']);
        $charge = mysqli_real_escape_string($conn, $_POST['charge']);

        $sql = "SELECT admission_id FROM admission 
                WHERE patient_id = '$patient_id' 
                ORDER BY admission_date DESC LIMIT 1";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $admission_id = mysqli_fetch_assoc($res)['admission_id'];

            $testSql = "SELECT test_id FROM test 
                        WHERE patient_id = '$patient_id' 
                        ORDER BY test_date DESC LIMIT 1";
            $testRes = mysqli_query($conn, $testSql);
            $test_id = ($testRes && mysqli_num_rows($testRes) > 0) ? mysqli_fetch_assoc($testRes)["test_id"] : "NULL";

            $insertSql = "INSERT INTO diagnosis_ipd (admission_id, test_id, test_required, test_done, diagnosis_details, observations, patient_condition, decision, charge) 
                          VALUES ('$admission_id', " . ($test_id ? "'$test_id'" : "NULL") . ", '$test_required', '$test_done', '$diagnosis_details', '$observations', '$patient_condition', '$decision', '$charge')";

            if (mysqli_query($conn, $insertSql)) {
                if ($decision === "discharge") {
                    header("Location: new_discharge.php");
                    exit();
                } elseif ($decision === "department change") {
                    header("Location: change_doc_dept.php");
                    exit();
                } elseif ($decision === "procedure") {
                    header("Location: add_procedure.php");
                    exit();
                } else {
                    $msg = "Diagnosis added successfully.";
                }
            } else {
                $err = "Error inserting diagnosis.";
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
    <title>Add IPD Diagnosis</title>
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
</head>
<body>

<h2>Add IPD Diagnosis Report</h2>

<?php if ($err): ?><p class="error"><?= $err ?></p><?php endif; ?>
<?php if ($msg): ?><p class="success"><?= $msg ?></p><?php endif; ?>

<form method="post">
    Enter Patient Name:<br>
    <input type="text" name="patient_name" required><br>
    <button type="submit" name="search" class="btn">Search</button>
</form>

<?php if (!empty($patients)): ?>
    <h3>Select a patient to continue:</h3>
    <?php foreach ($patients as $p): ?>
        <div class="card" onclick="selectPatient(<?= htmlspecialchars(json_encode($p)) ?>)">
            <strong>ID:</strong> <?= $p['patient_id'] ?> |
            <strong>Name:</strong> <?= $p['patient_name'] ?> |
            <strong>Age:</strong> <?= $p['age'] ?> |
            <strong>DOB:</strong> <?= $p['dob'] ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div id="diagnosis-form" class="hidden">
    <form method="post">
        <input type="hidden" name="patient_id" id="patient_id">
        <p><strong>Selected Patient ID:</strong> <span id="pid_display"></span></p>

        Is any kind of test required?<br>
        <select name="test_required" required>
            <option value="">Select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br><br>

        Are the tests done yet?<br>
        <select name="test_done" required>
            <option value="">Select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br><br>

        Diagnosis details:<br>
        <input type="text" name="diagnosis_details" required><br><br>

        Observations:<br>
        <input type="text" name="observations" required><br><br>

        Patient's condition:<br>
        <select name="patient_condition" required>
            <option value="">Select</option>
            <option value="mild">Mild</option>
            <option value="moderate">Moderate</option>
            <option value="severe">Severe</option>
            <option value="deceased">Deceased</option>
        </select><br><br>

        Decision:<br>
        <select name="decision" required>
            <option value="">Select</option>
            <option value="discharge">Discharge</option>
            <option value="stay extended">Stay extended</option>
            <option value="department change">Change department</option>
            <option value="procedure">Procedure</option>
        </select><br><br>

        Charge in INR:<br>
        <input type="number" name="charge" step="0.01" required><br><br>

        <button type="submit" name="submit_diagnosis" class="btn">Submit Diagnosis</button>
    </form>
</div>

<script>
function selectPatient(patient) {
    document.getElementById('diagnosis-form').classList.remove('hidden');
    document.getElementById('patient_id').value = patient.patient_id;
    document.getElementById('pid_display').innerText = patient.patient_id;
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}
</script>

<br>
<a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
