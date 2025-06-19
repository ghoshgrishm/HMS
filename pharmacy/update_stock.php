<?php
include("../database.php");

$results = [];
$updateData = null;
$success = "";
$error = "";
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_stock"])) {
        $stmt = $conn->prepare("UPDATE stock SET 
            medicine_name = ?, company = ?, batch = ?, quantity = ?, expiry_date = ?, 
            unit_type = ?, cost_per_unit = ?, status = ?, last_updated = NOW()
            WHERE stock_id = ?");
        $stmt->bind_param("sssissdsi",
            $_POST['medicine_name'],
            $_POST['company'],
            $_POST['batch'],
            $_POST['quantity'],
            $_POST['expiry_date'],
            $_POST['unit_type'],
            $_POST['cost_per_unit'],
            $_POST['status'],
            $_POST['stock_id']
        );
        if ($stmt->execute()) {
            $success = "Stock updated successfully.";
        } else {
            $error = "Error updating stock.";
        }
    } elseif (isset($_POST["stock_id"])) {
        $id = $_POST["stock_id"];
        $stmt = $conn->prepare("SELECT * FROM stock WHERE stock_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $updateData = $stmt->get_result()->fetch_assoc();
    } elseif (isset($_POST["medicine_name"])) {
        $name = trim($_POST["medicine_name"]);
        $stmt = $conn->prepare("SELECT * FROM stock WHERE medicine_name LIKE ?");
        $search = "%$name%";
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $results = $stmt->get_result();
        $searchPerformed = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Stock</title>
    <style>
        /* body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        .box { padding: 15px; background: #e0f7fa; border: 1px solid #00796b; border-radius: 10px; margin: 5px; cursor: pointer; width: 200px; }
        .flex-container { display: flex; flex-wrap: wrap; gap: 10px; }
        .success-message { color: green; margin-bottom: 10px; }
        input, select { margin-bottom: 10px; width: 100%; padding: 5px; }
        form { max-width: 400px; background: white; padding: 20px; border-radius: 10px; }
        .error-message { color: red; margin-top: 10px; font-weight: bold; } */
    </style>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<h1>Search and Update Medicine Stock</h1>

<?php if ($success): ?>
    <p class="success-message"><?= $success ?></p>
<?php endif; ?>
<?php if ($error): ?>
    <p class="error-message"><?= $error ?></p>
<?php endif; ?>

<!-- Search Form -->
<form method="post">
    <input type="text" name="medicine_name" placeholder="Enter medicine name" required>
    <button type="submit">Search</button>
</form>

<!-- Show Results -->
<?php if ($searchPerformed): ?>
    <?php if ($results->num_rows > 0): ?>
        <h3>Click a medicine to update:</h3>
        <div class="flex-container">
            <?php while ($row = $results->fetch_assoc()): ?>
                <form method="post">
                    <input type="hidden" name="stock_id" value="<?= $row['stock_id'] ?>">
                    <button class="box" type="submit">
                        <b><?= htmlspecialchars($row['medicine_name']) ?></b><br>
                        Batch: <?= $row['batch'] ?><br>
                        Qty: <?= $row['quantity'] ?>
                    </button>
                </form>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="error-message">No such medicine found.</p>
    <?php endif; ?>
<?php endif; ?>

<!-- Update Form -->
<?php if ($updateData): ?>
    <h3>Update Stock Details</h3>
    <form method="post">
        <input type="hidden" name="stock_id" value="<?= $updateData['stock_id'] ?>">
        <input type="hidden" name="update_stock" value="1">

        Medicine Name:
        <input type="text" name="medicine_name" value="<?= $updateData['medicine_name'] ?>">

        Company:
        <input type="text" name="company" value="<?= $updateData['company'] ?>">

        Batch:
        <input type="text" name="batch" value="<?= $updateData['batch'] ?>">

        Quantity:
        <input type="number" name="quantity" value="<?= $updateData['quantity'] ?>">

        Expiry Date:
        <input type="date" name="expiry_date" value="<?= $updateData['expiry_date'] ?>">

        Unit Type:
        <select name="unit_type">
            <?php
            $units = ['tablets', 'capsules', 'strips', 'bottles'];
            foreach ($units as $unit) {
                $selected = $unit == $updateData['unit_type'] ? 'selected' : '';
                echo "<option $selected>$unit</option>";
            }
            ?>
        </select>

        Cost Per Unit:
        <input type="number" step="0.01" name="cost_per_unit" value="<?= $updateData['cost_per_unit'] ?>">

        Status:
        <select name="status">
            <option <?= $updateData['status'] == 'available' ? 'selected' : '' ?>>available</option>
            <option <?= $updateData['status'] == 'out of stock' ? 'selected' : '' ?>>out of stock</option>
        </select>

        <button type="submit">Update Stock</button>
    </form>
<?php endif; ?>

</body>
</html>
