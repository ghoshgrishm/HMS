<?php
// Database connection and processing
include("database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $sql = "SELECT * FROM patient WHERE name LIKE '%$name%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row; // Store results for display later
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
    <title>Patient Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .patient-record {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .search-btn, .add-patient-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .add-patient-btn {
            background-color: #2196F3;
            margin-top: 10px;
        }
        .search-btn:hover, .add-patient-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <h1>Patient Search</h1>
    
    <!-- Search Form -->
    <form method="post">
        <label for="name">Enter Patient Name:</label><br>
        <input type="text" id="name" name="name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <!-- Display Search Results -->
    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $patient): ?>
                <div class="patient-record">
                    <strong>ID:</strong> <?= htmlspecialchars($patient['patient_id']) ?><br>
                    <strong>Name:</strong> <?= htmlspecialchars($patient['name']) ?><br>
                    <strong>Age:</strong> <?= htmlspecialchars($patient['age']) ?><br>
                    <strong>Sex:</strong> <?= htmlspecialchars($patient['sex']) ?><br>
                    <strong>Contact:</strong> <?= htmlspecialchars($patient['contact_no']) ?><br>
                    <strong>Email:</strong> <?= htmlspecialchars($patient['email_id']) ?><br>
                    <strong>Address:</strong> <?= htmlspecialchars($patient['residential_add']) ?><br>
                    <strong>Medical History:</strong> <?= htmlspecialchars($patient['medical_history']) ?><br>
                    <strong>First Visit:</strong> <?= htmlspecialchars($patient['first_visit']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No patients found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Add New Patient Button -->
    <br>
    <a href="add_patient.php" class="add-patient-btn">Add New Patient:</a>
</body>
</html>