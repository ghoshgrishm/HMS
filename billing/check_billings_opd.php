<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $sql = "SELECT p.patient_name, m.billing_id, m.appointment_id, m.amount, m.payment_received, m.payment_mode, m.payment_date
            FROM billing_opd m
            JOIN appointment a ON m.appointment_id = a.appointment_id
            JOIN patient p ON a.patient_id = p.patient_id
            WHERE LOWER(p.patient_name) LIKE LOWER('%$name%')";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    }
    $searchPerformed = true;
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check billings</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Check bills of OPD patients</h1>
    
    <form method="post">
        <label for="patient_name">Enter Patient's Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $billing): ?>
                <div class="billing-record">
                    <strong>Patient's name:</strong> <?= htmlspecialchars($billing['patient_name']) ?><br>
                    <strong>Billing ID:</strong> <?= htmlspecialchars($billing['billing_id']) ?><br>
                    <strong>Appointment ID:</strong> <?= htmlspecialchars($billing['appointment_id']) ?><br>
                    <strong>Amount:</strong> <?= htmlspecialchars($billing['amount']) ?><br>
                    <strong>Payment Received:</strong> <?= htmlspecialchars($billing['payment_received']) ?><br>
                    <strong>Payment Mode:</strong> <?= htmlspecialchars($billing['payment_mode']) ?><br>
                    <strong>Payment Date:</strong> <?= htmlspecialchars($billing['payment_date']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No billing records found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_billing_opd.php" class="new-billing-btn">Add new Billing for OPD:</a>
    <br>
    <a href="home_billing.php" class="go-home-btn">Go home</a>
</body>
</html>