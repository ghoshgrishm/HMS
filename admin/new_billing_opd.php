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

        $sql = "SELECT appointment_id FROM appointment 
                WHERE patient_id = '$patient_id' 
                ORDER BY appointment_date DESC LIMIT 1";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $appointment_id = mysqli_fetch_assoc($res)['appointment_id'];

            $q = "
                SELECT 
                    COALESCE(SUM(d.charge), 0) AS diagnosis_charge,
                    COALESCE(SUM(p.charge), 0) AS prescription_charge
                FROM appointment a
                LEFT JOIN diagnosis_opd d ON a.appointment_id = d.appointment_id
                LEFT JOIN prescription p ON a.appointment_id = p.appointment_id
                WHERE a.appointment_id = '$appointment_id'
            ";

            $res2 = mysqli_query($conn, $q);
            if ($res2 && $row = mysqli_fetch_assoc($res2)) {
                $total = $row['diagnosis_charge'] + $row['prescription_charge'];
                if ($discount > $total) {
                    $err = "Discount cannot be greater than total charges.";
                }
                else{
                    $final_amount = $total - $discount;
                }
                $insertSql = "INSERT INTO billing_opd (appointment_id, amount, payment_received, payment_mode, payment_date)
                              VALUES ('$appointment_id', '$final_amount', '$payment_received', '$payment_mode', CURRENT_TIMESTAMP)";

                if (mysqli_query($conn, $insertSql)) {
                    $msg = "Billing submitted successfully. Final Amount: ₹$final_amount";
                } else {
                    $err = "Error inserting billing.";
                }
            } else {
                $err = "Could not calculate charges.";
            }
        } else {
            $err = "No appointment found for selected patient.";
        }
    }
}
?>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
