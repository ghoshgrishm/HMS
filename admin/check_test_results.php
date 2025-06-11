<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['patient_name'])) {
        $name = mysqli_real_escape_string($conn, $_POST['patient_name']);
        
        $sql = "SELECT t.test_id, t.test_type, t.results, p.patient_name AS patient_name
        FROM test t
        JOIN admission a ON t.admission_id = a.admission_id
        JOIN patient p ON a.patient_id = p.patient_id
        WHERE LOWER(p.patient_name) LIKE LOWER('%$name%')";
        
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
        }

        $searchPerformed = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Patient Test Records</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

    <h1>Check Patient Test Records</h1>

    <form method="post">
        <label for="name">Enter Patient Name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit" class="btn search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $test): ?>
                <div class="test-record">
                    <strong>Patient Name:</strong> <?= htmlspecialchars($test['patient_name']) ?><br>
                    <strong>Test ID:</strong> <?= htmlspecialchars($test['test_id']) ?><br>
                    <strong>Test Name:</strong> <?= htmlspecialchars($test['test_name']) ?><br>
                    <strong>Test Date:</strong> <?= htmlspecialchars($test['test_date']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No test records found for the given patient name.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_test.php" class="new-test-btn">Add New Test</a>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>

</body>
</html>
