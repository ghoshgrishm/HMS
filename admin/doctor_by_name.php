<?php
include("database.php");

$searchResults = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_name = mysqli_real_escape_string($conn, $_POST["doctor_name"]);
    $sql = "SELECT doctor.*, department.dept_name 
            FROM doctor 
            LEFT JOIN department ON doctor.department_id = department.department_id
            WHERE LOWER(doctor.doctor_name) LIKE LOWER('%$doctor_name%')";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search by Name</title>
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
    <h1>Search by Name</h1>
    
    <form method="post">
        <label for="name">Enter the Doctor's name:</label><br>
        <input type="text" id="doctor_name" name="doctor_name" required>
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
                    <strong>Slots:</strong><br>
                    <strong>8:00 - 9:00</strong> <?= htmlspecialchars($doctor['eight_to_nine']) ?><br>
                    <strong>9:00 - 10:00</strong> <?= htmlspecialchars($doctor['nine_to_ten']) ?><br>
                    <strong>10:00 - 11:00</strong> <?= htmlspecialchars($doctor['ten_to_eleven']) ?><br>
                    <strong>11:00 - 12:00</strong> <?= htmlspecialchars($doctor['eleven_to_twelve']) ?><br>
                    <strong>12:00 - 13:00</strong> <?= htmlspecialchars($doctor['twelve_to_one']) ?><br>
                    <strong>13:00 - 14:00</strong> <?= htmlspecialchars($doctor['one_to_two']) ?><br>
                    <strong>14:00 - 15:00</strong> <?= htmlspecialchars($doctor['two_to_three']) ?><br>
                    <strong>15:00 - 16:00</strong> <?= htmlspecialchars($doctor['three_to_four']) ?><br>
                    <strong>16:00 - 17:00</strong> <?= htmlspecialchars($doctor['four_to_five']) ?><br>
                    <strong>17:00 - 18:00</strong> <?= htmlspecialchars($doctor['five_to_six']) ?><br>
                    <strong>18:00 - 19:00</strong> <?= htmlspecialchars($doctor['six_to_seven']) ?><br>
                    <strong>19:00 - 20:00</strong> <?= htmlspecialchars($doctor['seven_to_eight']) ?><br>
                    <strong>20:00 - 21:00</strong> <?= htmlspecialchars($doctor['eight_to_nine_pm']) ?><br>
                    <strong>21:00 - 22:00</strong> <?= htmlspecialchars($doctor['nine_to_ten_pm']) ?><br>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No doctors found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>