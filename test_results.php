<?php
include("database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        
        $sql = "SELECT t.test_id, t.test_type, t.results, p.name AS patient_name
        FROM test t
        JOIN admission a ON t.admission_id = a.admission_id
        JOIN patient p ON a.patient_id = p.patient_id
        WHERE p.name LIKE '%$name%'";
        
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .record-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            text-decoration: none;
        }
        .search-btn {
            background-color: #4CAF50;
        }
        .action-btn {
            background-color: #2196F3;
            margin-right: 10px;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <h1>Check Patient Test Records</h1>

    <form method="post">
        <label for="name">Enter Patient Name:</label><br>
        <input type="text" id="name" name="name" required>
        <button type="submit" class="btn search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $test): ?>
                <div class="record-box">
                    <strong>Patient Name:</strong> <?= htmlspecialchars($test['patient_name']) ?><br>
                    <strong>Test ID:</strong> <?= htmlspecialchars($test['test_id']) ?><br>
                    <strong>Test Name:</strong> <?= htmlspecialchars($test['test_name']) ?><br>
                    <strong>Test Date:</strong> <?= htmlspecialchars($test['test_date']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No test records found for the given patient name.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_test.php" class="btn action-btn">Add New Test</a>
    <br>
    <br>
    <a href="index.php" class="btn action-btn">Go Home</a>

</body>
</html>
