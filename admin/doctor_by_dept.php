<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept_name = mysqli_real_escape_string($conn, $_POST["dept_name"]);
    $sql = "SELECT doctor.*, department.dept_name 
            FROM doctor 
            LEFT JOIN department ON doctor.department_id = department.department_id 
            WHERE LOWER(department.dept_name) LIKE LOWER('%$dept_name%')";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
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
    <title>Search by Department</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .doctor-record {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .search-btn, .go-home-btn, .doctor-slots-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .go-home-btn {
            background-color: #2196F3;
            margin-top: 10px;
        }
        .search-btn:hover, .go-home-btn:hover, .doctor-slots-btn:hover {
            opacity: 0.9;
        }
    </style>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Search by Department</h1>
    
    <form method="post">
        <label for="name">Enter the Department you want to check:</label><br>
        <input type="text" id="dept_name" name="dept_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $doctor): ?>
                <div class="doctor-record">
                    <strong>Doctor ID:</strong> <?= htmlspecialchars($doctor['doctor_id']) ?><br>
                    <strong>Name:</strong> <?= htmlspecialchars($doctor['doctor_name']) ?><br>
                    <strong>Specialty:</strong> <?= htmlspecialchars($doctor['specialty']) ?><br>
                    <strong>Department:</strong> <?= htmlspecialchars($doctor['dept_name']) ?><br>
                    <a href="doctor_slots.php?doctor_id=<?= $doctor['doctor_id'] ?>" class="new-patient-btn">Check doctor slots</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No departments found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
