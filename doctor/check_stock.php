<?php
include("../database.php");
$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["medicine_name"]);
    $sql = "SELECT * FROM stock WHERE LOWER(medicine_name) LIKE LOWER('%$name%')";
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
    <title>Document</title>
</head>
<body>
    <h1>Check Medicine Stock</h1>
    <form method="post">
        Enter the name of the medicine:
        <input type="text" id="medicine_name" name="medicine_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $stock): ?>
                <div class="medicine-record">
                    <strong>Stock ID:</strong> <?= htmlspecialchars($stock['stock_id']) ?><br>
                    <strong>Medicine name:</strong> <?= htmlspecialchars($stock['medicine_name']) ?><br>
                    <strong>Manufacturing company:</strong> <?= htmlspecialchars($stock['company']) ?><br>
                    <strong>Batch:</strong> <?= htmlspecialchars($stock['batch']) ?><br>
                    <strong>Quantity:</strong> <?= htmlspecialchars($stock['quantity']) ?><br>
                    <strong>Purchase date:</strong> <?= htmlspecialchars($stock['purchase_date']) ?><br>
                    <strong>Expiry date:</strong> <?= htmlspecialchars($stock['expiry_date']) ?><br>
                    <strong>Unit type:</strong> <?= htmlspecialchars($stock['unit_type']) ?><br>
                    <strong>Cost per unit:</strong> <?= htmlspecialchars($stock['cost_per_unit']) ?><br>
                    <strong>Status:</strong> <?= htmlspecialchars($stock['status']) ?><br>
                    <strong>Last updated:</strong> <?= htmlspecialchars($stock['last_updated']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No medicine found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_stock.php" class="new-stock-btn">Add New Medicine:</a>
    <br>
    <a href="home_doctor.php" class="go-home-btn">Go home</a>
</body>
</html>