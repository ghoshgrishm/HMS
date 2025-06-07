<?php
    include("database.php");
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

    </style>
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