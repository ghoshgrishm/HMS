<?php
include("../database.php");

$medicine_name = $company = $batch = $quantity = $purchase_date = $expiry_date = $unit_type = $cost_per_unit = "";
$err = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $medicine_name = trim($_POST["medicine_name"]);
    $company = trim($_POST["company"]);
    $batch = trim($_POST["batch"]);
    $quantity = trim($_POST["quantity"]);
    $purchase_date = trim($_POST["purchase_date"]);
    $expiry_date = trim($_POST["expiry_date"]);
    $unit_type = trim($_POST["unit_type"]);
    $cost_per_unit = trim($_POST["cost_per_unit"]);

    if (empty($medicine_name)) {
        $err = "Please enter the medicine name";
    } elseif (empty($company)) {
        $err = "Please enter the manufacturer's name";
    } elseif (empty($batch)) {
        $err = "Please enter a valid batch";
    } elseif (empty($quantity) || !is_numeric($quantity)) {
        $err = "Please enter the quantity of the medicine";
    } elseif (empty($purchase_date)) {
        $err = "Please enter a valid purchase date";
    } elseif (empty($expiry_date)) {
        $err = "Please enter a valid expiry date";
    } elseif (empty($unit_type)) {
        $err = "Please select the unit type";
    } elseif (empty($cost_per_unit) || !is_numeric($cost_per_unit)) {
        $err = "Please enter the cost per unit";
    }

    if (!$err) {
        $medicine_name = mysqli_real_escape_string($conn, $medicine_name);
        $company = mysqli_real_escape_string($conn, $company);
        $batch = mysqli_real_escape_string($conn, $batch);
        $quantity = mysqli_real_escape_string($conn, $quantity);
        $purchase_date = mysqli_real_escape_string($conn, $purchase_date);
        $expiry_date = mysqli_real_escape_string($conn, $expiry_date);
        $unit_type = mysqli_real_escape_string($conn, $unit_type);
        $cost_per_unit = mysqli_real_escape_string($conn, $cost_per_unit);

        $sql = "INSERT INTO stock (medicine_name, company, batch, quantity, purchase_date, expiry_date, unit_type, cost_per_unit, status)
                VALUES ('$medicine_name', '$company', '$batch', '$quantity', '$purchase_date', '$expiry_date', '$unit_type', '$cost_per_unit', 'available')";

        if (mysqli_query($conn, $sql)) {
            $success = "Medicine record updated successfully";
        } else {
            $err = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add New Medicine</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Make entries for new medicine</h1>

    <?php if ($err): ?>
        <p class="error-message" style="color:red;"><?= htmlspecialchars($err) ?></p>
    <?php elseif ($success): ?>
        <p class="success-message" style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        Medicine name:<br>
        <input type="text" name="medicine_name" required><br>
        Manufacturing company:<br>
        <input type="text" name="company" required><br>
        Batch:<br>
        <input type="text" name="batch" required><br>
        Quantity:<br>
        <input type="number" name="quantity" required><br>
        Purchase date:<br>
        <input type="date" name="purchase_date" required><br>
        Expiry date:<br>
        <input type="date" name="expiry_date" required><br>
        Unit type:<br>
        <select name="unit_type" required>
            <option value="tablets">Tablets</option>
            <option value="capsules">Capsules</option>
            <option value="strips">Strips</option>
            <option value="bottles">Bottles</option>
            <option value="ml">mL</option>
            <option value="vials">Vials</option>
            <option value="ampoules">Ampoules</option>
            <option value="sachets">Sachets</option>
            <option value="inhalers">Inhalers</option>
            <option value="patches">Patches</option>
            <option value="units">Units</option>
            <option value="tubes">Tubes</option>
            <option value="gm">gm</option>
        </select><br>
        Cost per unit:<br>
        <input type="number" name="cost_per_unit" step="0.01" min="0" required><br>
        <input type="submit" class="submit-btn" name="submit" value="Submit"><br>
    </form>

    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
