<?php
include("../database.php");

// Handle POST actions before output
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    <a href="home_reception.php" class="go-home-btn">Go home</a>
</body>
</html>
