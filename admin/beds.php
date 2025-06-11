<?php
include("database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bed_number = mysqli_real_escape_string($conn, $_POST["bed_number"]);
    $sql = "SELECT b.bed_id, b.bed_number, b.bed_status, d.dept_name
            FROM bed b
            JOIN department d ON b.department_id = d.department_id
            WHERE LOWER(b.bed_number) = LOWER('$bed_number')";
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
    <title>Bed Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .bed-status {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
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
</head>
<body>
    <h1>Check Bed Status</h1>
    
    <form method="post">
        Enter the bed number to check its status:<br>
        <input type="text" name="bed_number" required>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($searchPerformed): ?>
        <h2>Search Results</h2>
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $bed): ?>
                <div class="bed-record">
                    <strong>Bed ID:</strong> <?= htmlspecialchars($bed['bed_id']) ?><br>
                    <strong>Bed Number:</strong> <?= htmlspecialchars($bed['bed_number']) ?><br>
                    <strong>Department:</strong> <?= htmlspecialchars($bed['dept_name']) ?><br>
                    <strong>Status:</strong> <?= htmlspecialchars($bed['bed_status']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No beds found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>
    
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>