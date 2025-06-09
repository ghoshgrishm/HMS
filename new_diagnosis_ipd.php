<?php
include("database.php");

$patient_name = $test_required = $test_done = $diagnosis_details = $observations = $patient_condition = $decision = $charge = "";
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = trim($_POST["patient_name"]);
    $test_required = trim($_POST["test_required"]);
    $test_done = trim($_POST["test_done"]);
    $diagnosis_details = trim($_POST["diagnosis_details"]);
    $observations = trim($_POST["observations"]);
    $patient_condition = trim($_POST["patient_condition"]);
    $decision = trim($_POST["decision"]);
    $charge = trim($_POST["charge"]);

    if (empty($patient_name) || empty($test_required) || empty($test_done) || empty($diagnosis_details)
        || empty($observations) || empty($patient_condition) || empty($decision) || empty($charge)) {
        $err = "Please fill in all the fields.";
    } else {
        $patient_name = mysqli_real_escape_string($conn, $patient_name);
        $test_required = mysqli_real_escape_string($conn, $test_required);
        $test_done = mysqli_real_escape_string($conn, $test_done);
        $diagnosis_details = mysqli_real_escape_string($conn, $diagnosis_details);
        $observations = mysqli_real_escape_string($conn, $observations);
        $patient_condition = mysqli_real_escape_string($conn, $patient_condition);
        $decision = mysqli_real_escape_string($conn, $decision);
        $charge = mysqli_real_escape_string($conn, $charge);

        $sql = "SELECT a.admission_id, p.patient_id 
                FROM admission a 
                JOIN patient p ON a.patient_id = p.patient_id 
                WHERE p.patient_name = '$patient_name' 
                ORDER BY a.admission_date DESC 
                LIMIT 1";

        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $admission_id = $row["admission_id"];
            $patient_id = $row["patient_id"];

            $testSql = "SELECT test_id 
                        FROM test 
                        WHERE patient_id = '$patient_id' 
                        ORDER BY test_date DESC 
                        LIMIT 1";

            $testRes = mysqli_query($conn, $testSql);
            $test_id = ($testRes && mysqli_num_rows($testRes) > 0) ? mysqli_fetch_assoc($testRes)["test_id"] : null;

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
                    echo "<p style='color:green;'>Diagnosis added successfully. No redirection needed.</p>";
                }
            } else {
                echo "<p style='color:red;'>Error inserting diagnosis: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style='color:red;'>Patient not found or not admitted.</p>";
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add new diagnosis report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
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
    <h2>Add new IPD diagnosis report</h2>

    <?php
    if (!empty($err)) {
        echo "<p style='color:red;'>$err</p>";
    }
    ?>

    <form method="post">
        Patient's name:<br>
        <input type="text" name="patient_name" required><br><br>

        Is any kind of test required?<br>
        <select name="test_required" required>
            <option value="">Select option</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br><br>

        Are the tests done yet?<br>
        <select name="test_done" required>
            <option value="">Select option</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br><br>

        Diagnosis details:<br>
        <input type="text" name="diagnosis_details" required><br><br>

        Observations:<br>
        <input type="text" name="observations" required><br><br>

        Patient's current Condition:<br>
        <select name="patient_condition" required>
            <option value="">Select option</option>
            <option value="mild">Mild</option>
            <option value="moderate">Moderate</option>
            <option value="severe">Severe</option>
            <option value="deceased">Deceased</option>
        </select><br><br>

        Decision:<br>
        <select name="decision" required>
            <option value="">Select option</option>
            <option value="discharge">Discharge</option>
            <option value="stay extended">Stay extended</option>
            <option value="department change">Change department</option>
            <option value="procedure">Procedure</option>
        </select><br><br>

        Charge:<br>
        <input type="number" name="charge" step="0.01" required><br>

        <input type="submit" value="Submit"><br><br>
    </form>

    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
