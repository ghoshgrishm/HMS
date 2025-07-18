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

    if (isset($_POST['submit_billing'])) {
        $patient_id = $_POST['patient_id'];
        $payment_received = mysqli_real_escape_string($conn, $_POST['payment_received']);
        $payment_mode = mysqli_real_escape_string($conn, $_POST['payment_mode']);
        $discount = floatval($_POST['discount']);

        $sql = "SELECT admission_id FROM admission 
                WHERE patient_id = '$patient_id' 
                ORDER BY admission_date DESC LIMIT 1";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $admission_id = mysqli_fetch_assoc($res)['admission_id'];

            $q = "
                SELECT 
                    COALESCE(SUM(d.charge), 0) AS diagnosis_charge,
                    COALESCE(SUM(p.charge), 0) AS procedure_charge,
                    COALESCE(SUM(m.charge), 0) AS medication_charge
                FROM admission a
                LEFT JOIN diagnosis_ipd d ON a.admission_id = d.admission_id
                LEFT JOIN procedure p ON a.admission_id = p.admission_id
                LEFT JOIN medication m ON a.admission_id = m.admission_id
                WHERE a.admission_id = '$admission_id'
            ";

            $res2 = mysqli_query($conn, $q);
            if ($res2 && $row = mysqli_fetch_assoc($res2)) {
                $total = $row['diagnosis_charge'] + $row['procedure_charge'] + $row['medication_charge'];
                if ($discount > $total) {
                    $err = "Discount cannot be greater than total charges.";
                }
                else{
                    $final_amount = $total - $discount;
                }

                $insertSql = "INSERT INTO billing_ipd (admission_id, amount, payment_received, payment_mode, payment_date)
                              VALUES ('$admission_id', '$final_amount', '$payment_received', '$payment_mode', CURRENT_TIMESTAMP)";

                if (mysqli_query($conn, $insertSql)) {
                    $msg = "Billing submitted successfully. Final Amount: ₹$final_amount";
                } else {
                    $err = "Error inserting billing.";
                }
            } else {
                $err = "Could not calculate charges.";
            }
        } else {
            $err = "No admission found for selected patient.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/styles.css">
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
</head>
<body>
    <h1>Add new Bill for IPD patient</h1>

    <?php if ($err): ?><p class="error-message"><?= $err ?></p><?php endif; ?>
    <?php if ($msg): ?><p class="success-message"><?= $msg ?></p><?php endif; ?>

    <form method="post">
        Search Patient Name:<br>
        <input type="text" name="patient_name" required>
        <button type="submit" name="search">Search</button>
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

    <div id="billing-form" class="hidden">
        <form method="post">
            <input type="hidden" name="patient_id" id="patient_id">
            <p><strong>Selected Patient ID:</strong> <span id="pid_display"></span></p>

            Discount (₹):<br>
            <input type="number" name="discount" min="0" step="0.01" value="0" required><br><br>

            Payment Received?<br>
            <select name="payment_received" required>
                <option value="">Select</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select><br><br>

            Payment Mode:<br>
            <select name="payment_mode" required>
                <option value="">Select</option>
                <option value="cash">Cash</option>
                <option value="credit card">Credit Card</option>
                <option value="debit card">Debit Card</option>
                <option value="upi">UPI</option>
                <option value="net banking">Net Banking</option>
                <option value="insurance">Insurance</option>
                <option value="emi">EMI</option>
                <option value="government scheme">Government Scheme</option>
                <option value="cheque">Cheque</option>
            </select><br><br>

            <button type="submit" name="submit_billing" class="btn">Submit Billing</button>
        </form>
    </div>
    <script>
        function selectPatient(patient) {
            document.getElementById('billing-form').classList.remove('hidden');
            document.getElementById('patient_id').value = patient.patient_id;
            document.getElementById('pid_display').innerText = patient.patient_id;
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    </script>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
