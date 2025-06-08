<?php
include("database.php");

$searchResults = [];
$searchPerformed = false;
$showNewAdmissionOption = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        
        $sql = "SELECT a.admission_id, p.name AS patient_name, d.dept_name AS department_name, b.bed_number, a.admission_date, a.predict_discharge
                FROM admission a
                JOIN patient p ON a.patient_id = p.patient_id
                JOIN department d ON a.department_id = d.department_id
                JOIN bed b ON a.bed_id = b.bed_id
                WHERE p.name LIKE '%$name%'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
        } else {
            $showNewAdmissionOption = true;
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
    <title>Admission Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .admission-record {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .search-btn, .new-admission-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .new-admission-btn {
            background-color: #2196F3;
            margin-top: 10px;
        }
        .search-btn:hover, .new-admission-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <h1>Admission Check</h1>
    
    <form method="post">
        <label for="name">Enter Patient Name:</label><br>
        <input type="text" id="name" name="name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $admission): ?>
                <div class="admission-record">
                    <strong>Admission ID:</strong> <?= htmlspecialchars($admission['admission_id']) ?><br>
                    <strong>Patient Name:</strong> <?= htmlspecialchars($admission['patient_name']) ?><br>
                    <strong>Patient's Condition:</strong> <?= htmlspecialchars($admission['patient_condition']) ?><br>
                    <strong>Department Name:</strong> <?= htmlspecialchars($admission['department_name']) ?><br>
                    <strong>Bed Number:</strong> <?= htmlspecialchars($admission['bed_number']) ?><br>
                    <strong>Admission Date:</strong> <?= htmlspecialchars($admission['admission_date']) ?><br>
                    <strong>Predicted Date of Discharge:</strong> <?= htmlspecialchars($admission['predict_discharge']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No admission found for this patient.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="new_admission.php" class="new-admission-btn">Make a new Admission</a>

    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
