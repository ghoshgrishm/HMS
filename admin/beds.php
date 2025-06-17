<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept_name = mysqli_real_escape_string($conn, $_POST["dept_name"]);
    $sql = "SELECT b.bed_id, b.bed_number, b.bed_status, d.dept_name
            FROM bed b
            JOIN department d ON b.department_id = d.department_id
            WHERE LOWER(d.dept_name) = LOWER('$dept_name')";
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
    <title>Search Beds by Department</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .bed-record {
            border: 2px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 16px;
        }
        .status-available {
            color: green;
            font-weight: bold;
        }
        .status-occupied {
            color: red;
            font-weight: bold;
        }
        .search-btn, .go-home-btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .go-home-btn {
            background-color: #2196F3;
            margin-top: 10px;
        }
        .search-btn:hover, .go-home-btn:hover {
            opacity: 0.9;
        }
    </style>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Check Bed Status by Department</h1>
    
    <form method="post">
        Enter the department name:<br>
        <input type="text" name="dept_name" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $bed): ?>
                <?php
                    $statusClass = strtolower($bed['bed_status']) === 'available' ? 'status-available' : 'status-occupied';
                ?>
                <div class="bed-record">
                    <strong>Bed Number:</strong> <?= htmlspecialchars($bed['bed_number']) ?><br>
                    <strong>Department:</strong> <?= htmlspecialchars($bed['dept_name']) ?><br>
                    <strong>Status:</strong> <span class="<?= $statusClass ?>"><?= htmlspecialchars($bed['bed_status']) ?></span><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message">No beds found in this department.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
