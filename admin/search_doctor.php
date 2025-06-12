<?php
include("../database.php");
$reset_msg = "";

// Handle POST actions before output
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Reset doctor slots if requested
    if (isset($_POST['reset_slots'])) {
        $sql = "UPDATE doctor SET
            eight_to_nine = 'available',
            nine_to_ten = 'available',
            ten_to_eleven = 'available',
            eleven_to_twelve = 'available',
            twelve_to_one = 'available',
            one_to_two = 'available',
            two_to_three = 'available',
            three_to_four = 'available',
            four_to_five = 'available',
            five_to_six = 'available',
            six_to_seven = 'available',
            seven_to_eight = 'available',
            eight_to_nine_pm = 'available',
            nine_to_ten_pm = 'available'
        WHERE
            eight_to_nine = 'booked' OR
            nine_to_ten = 'booked' OR
            ten_to_eleven = 'booked' OR
            eleven_to_twelve = 'booked' OR
            twelve_to_one = 'booked' OR
            one_to_two = 'booked' OR
            two_to_three = 'booked' OR
            three_to_four = 'booked' OR
            four_to_five = 'booked' OR
            five_to_six = 'booked' OR
            six_to_seven = 'booked' OR
            seven_to_eight = 'booked' OR
            eight_to_nine_pm = 'booked' OR
            nine_to_ten_pm = 'booked'";

        if (mysqli_query($conn, $sql)) {
            $reset_msg = "<p style='color: green;'>✅ All doctor slots have been reset for today.</p>";
        } else {
            $reset_msg = "<p style='color: red;'>❌ Error resetting slots: " . mysqli_error($conn) . "</p>";
        }
    }

    // Handle search redirect
    if (isset($_POST["search_type"])) {
        $search_type = trim($_POST["search_type"]);

        if ($search_type == "name") {
            header("Location: doctor_by_name.php");
            exit();
        } elseif ($search_type == "department") {
            header("Location: doctor_by_dept.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Doctors</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .btn {
            padding: 10px 20px;
            margin: 5px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #1976D2;
        }
        .go-home-btn {
            padding: 8px 15px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .go-home-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <h1>Search for Doctors</h1>
    <h2>Search doctor by:</h2>

    <form method="post">
        <button type="submit" name="search_type" value="name" class="btn">Search by Name</button><br>
        <button type="submit" name="search_type" value="department" class="btn">Search by Department</button>
    </form>

    <br><br>

    <?= $reset_msg ?>
    <form method="post" onsubmit="return confirm('Reset all doctor slots for today?');">
        <button type="submit" name="reset_slots" class="btn">🔄 Reset Doctor Slots</button>
    </form>

    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>
