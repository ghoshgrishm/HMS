<?php
include("../database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h1>Search doctor by:</h1>

    <button type="submit" name="search_type" value="name" class="btn">Search by Name</button><br>
    <button type="submit" name="search_type" value="department" class="btn">Search by Department</button><br>
</form>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $search_type = trim($_POST["search_type"]);

        if ($search_type == "name") {
            header("Location: doctor_by_name.php");
            exit();
        }
        elseif ($search_type == "department") {
            header("Location: doctor_by_dept.php");
            exit();
        }
        else{
            echo "<p style='color:red;'>Please select a visit type.</p>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
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
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>