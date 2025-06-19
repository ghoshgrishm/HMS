<?php
include("../database.php");

$patient_id = isset($_GET['patient_id']) ? mysqli_real_escape_string($conn, $_GET['patient_id']) : null;

if (!$patient_id) {
    echo "<p class='error-message'>No patient selected.</p>";
    exit;
}

$patientDetails = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM patient WHERE patient_id = '$patient_id'"));

$admissions = mysqli_query($conn, "SELECT * FROM admission WHERE patient_id = '$patient_id'");
$appointments = mysqli_query($conn, "SELECT * FROM appointment WHERE patient_id = '$patient_id'");
$adm_count = mysqli_num_rows($admissions);
$app_count = mysqli_num_rows($appointments);
$test_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM test WHERE admission_id IN (SELECT admission_id FROM admission WHERE patient_id = '$patient_id') OR appointment_id IN (SELECT appointment_id FROM appointment WHERE patient_id = '$patient_id')"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Summary</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .card { border: 1px solid #ccc; padding: 10px; margin: 10px 0; border-radius: 8px; background-color: #f9f9f9; }
        .card:hover { background-color: #e6f7ff; }
        .summary-section { background-color: #fff; border-radius: 10px; padding: 15px; margin-top: 20px; }
    </style>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<h1>Patient Summary</h1>

<?php if ($patientDetails): ?>
    <div class="patient-record">
        <h2><?= htmlspecialchars($patientDetails['patient_name']) ?></h2>
        <p><strong>ID:</strong> <?= $patientDetails['patient_id'] ?> | <strong>Age:</strong> <?= $patientDetails['age'] ?> | <strong>Sex:</strong> <?= $patientDetails['sex'] ?></p>
        <p><strong>Contact:</strong> <?= $patientDetails['contact_no'] ?> | <strong>Email:</strong> <?= $patientDetails['email_id'] ?></p>
        <p><strong>Medical History:</strong> <?= $patientDetails['medical_history'] ?></p>
        <p><strong>Tests done:</strong> <?= $test_count ?> | <strong>Number of times admitted:</strong> <?= $adm_count ?> | <strong>Number of appointments booked:</strong> <?= $app_count ?></p>

        <h3>Admissions</h3>
        <?php mysqli_data_seek($admissions, 0); while ($adm = mysqli_fetch_assoc($admissions)): ?>
            <div class="card">
                <p><strong>Admission ID:</strong> <?= $adm['admission_id'] ?> | <strong>Date:</strong> <?= $adm['admission_date'] ?> | <strong>Condition:</strong> <?= $adm['patient_condition'] ?></p>
                <?php
                    $adm_id = $adm['admission_id'];
                    $diag = mysqli_query($conn, "SELECT diagnosis_details FROM diagnosis_ipd WHERE admission_id = '$adm_id'");
                    $proc = mysqli_query($conn, "SELECT procedure_type FROM procedure_ipd WHERE admission_id = '$adm_id'");
                    $med = mysqli_query($conn, "SELECT medicine_details, dosage FROM medication WHERE admission_id = '$adm_id'");
                    $dis = mysqli_query($conn, "SELECT * FROM discharge WHERE admission_id = '$adm_id'");
                    $bill = mysqli_query($conn, "SELECT * FROM billing_ipd WHERE admission_id = '$adm_id'");
                    $tests = mysqli_query($conn, "SELECT test_type FROM test WHERE admission_id = '$adm_id'");

                    if (mysqli_num_rows($diag)) { echo "<p><strong>Diagnoses:</strong><ul>"; while ($d = mysqli_fetch_assoc($diag)) echo "<li>{$d['diagnosis_details']}</li>"; echo "</ul></p>"; }
                    if (mysqli_num_rows($proc)) { echo "<p><strong>Procedures:</strong><ul>"; while ($p = mysqli_fetch_assoc($proc)) echo "<li>{$p['procedure_type']}</li>"; echo "</ul></p>"; }
                    if (mysqli_num_rows($med)) { echo "<p><strong>Medications:</strong><ul>"; while ($m = mysqli_fetch_assoc($med)) echo "<li>{$m['medicine_details']} - {$m['dosage']}</li>"; echo "</ul></p>"; }
                    if (mysqli_num_rows($tests)) { echo "<p><strong>Tests:</strong><ul>"; while ($t = mysqli_fetch_assoc($tests)) echo "<li>{$t['test_type']}</li>"; echo "</ul></p>"; }
                    if (mysqli_num_rows($bill)) { while ($b = mysqli_fetch_assoc($bill)) echo "<p><strong>Billing:</strong> ₹{$b['amount']} ({$b['payment_mode']}, {$b['payment_received']})</p>"; }
                    if (mysqli_num_rows($dis)) { while ($d = mysqli_fetch_assoc($dis)) echo "<p><strong>Discharge:</strong> {$d['discharge_date']} — {$d['notes']}</p>"; }
                ?>
            </div>
        <?php endwhile; ?>

        <h3>Appointments</h3>
        <?php mysqli_data_seek($appointments, 0); while ($app = mysqli_fetch_assoc($appointments)): ?>
            <div class="card">
                <p><strong>Appointment ID:</strong> <?= $app['appointment_id'] ?> | <strong>Date:</strong> <?= $app['appointment_date'] ?></p>
                <?php
                    $app_id = $app['appointment_id'];
                    $diag = mysqli_query($conn, "SELECT diagnosis_details FROM diagnosis_opd WHERE appointment_id = '$app_id'");
                    $presc = mysqli_query($conn, "SELECT * FROM prescription WHERE appointment_id = '$app_id'");
                    $bill = mysqli_query($conn, "SELECT * FROM billing_opd WHERE appointment_id = '$app_id'");
                    $tests = mysqli_query($conn, "SELECT test_type FROM test WHERE appointment_id = '$app_id'");

                    if (mysqli_num_rows($diag)) { echo "<p><strong>Diagnoses:</strong><ul>"; while ($d = mysqli_fetch_assoc($diag)) echo "<li>{$d['diagnosis_details']}</li>"; echo "</ul></p>"; }
                    if (mysqli_num_rows($presc)) {
                        echo "<p><strong>Prescriptions:</strong><ul>";
                        while ($p = mysqli_fetch_assoc($presc)) {
                            echo "<li>{$p['medicine_details']} ({$p['dosage']}, {$p['duration']})";
                            if ($p['follow_up']) echo " — Follow-up: {$p['follow_up']}";
                            echo "</li>";
                        }
                        echo "</ul></p>";
                    }
                    if (mysqli_num_rows($tests)) { echo "<p><strong>Tests:</strong><ul>"; while ($t = mysqli_fetch_assoc($tests)) echo "<li>{$t['test_type']}</li>"; echo "</ul></p>"; }
                    if (mysqli_num_rows($bill)) { while ($b = mysqli_fetch_assoc($bill)) echo "<p><strong>Billing:</strong> ₹{$b['amount']} ({$b['payment_mode']}, {$b['payment_received']})</p>"; }
                ?>
            </div>
        <?php endwhile; ?>

    </div>
<?php else: ?>
    <p class="error-message">Patient not found.</p>
<?php endif; ?>

<br><a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
