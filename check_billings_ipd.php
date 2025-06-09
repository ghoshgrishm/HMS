<?php
include("database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $sql = "SELECT p.patient_name, m.billing_id, m.admission_id, m.amount, m.payment_received, m.payment_mode, m.payment_date
            FROM billing_ipd m
            JOIN admission a ON m.admission_id = a.admission_id
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
    <title>Check Billings for IPD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .billing-record {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .search-btn, .add-billing-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .add-billing-btn {
            background-color: #2196F3;
            margin-top: 10px;
        }
        .search-btn:hover, .add-billing-btn:hover {
            opacity: 0.9;
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
    <h1>Check billings</h1>
    
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
                    <strong>Admission ID:</strong> <?= htmlspecialchars($billing['admission_id']) ?><br>
                    <strong>Amount:</strong> <?= htmlspecialchars($billing['amount']) ?><br>
                    <strong>Payment Received:</strong> <?= htmlspecialchars($billing['payment_received']) ?><br>
                    <strong>Payment Mode:</strong> <?= htmlspecialchars($billing['payment_mode']) ?><br>
                    <strong>Payment Date:</strong> <?= htmlspecialchars($billing['payment_date']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No billing records found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_billing_ipd.php" class="add-billing-btn">Add new billing for IPD:</a>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>