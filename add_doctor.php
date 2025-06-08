<?php
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Doctor</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Enter the name of the new doctor:<br>
        <input type="text" name="name"><br>
        Enter the doctor's speciality:<br>
        <input type="text" name="special"><br>
        Enter the department name he shall be joining:<br>
        <input type="text" name="dept_name"><br>
        <input type="submit" value="Add Doctor">
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = trim($_POST["name"]);
    $dept_name = trim($_POST["dept_name"]);
    $special = trim($_POST["special"]);
    $err = "";

    if (empty($name)) {
        $err = "Please enter the new doctor's name";
    }
    elseif (empty($dept_name)) {
        $err = "Please specify the department he/she shall be joining";
    }
    elseif (empty($special)) {
        $err = "Please enter the doctor's speciality";
    }

    if (!$err) {
        $name = mysqli_real_escape_string($conn, $name);
        $dept_name = mysqli_real_escape_string($conn, $dept_name);
        $special = mysqli_real_escape_string($conn, $special);

        $sql_dept = "SELECT department_id FROM department WHERE dept_name = '$dept_name'";
        $result_dept = mysqli_query($conn, $sql_dept);

        if (mysqli_num_rows($result_dept) > 0) {
            $row = mysqli_fetch_assoc($result_dept);
            $dept_id = $row['department_id'];

            $sql = "INSERT INTO doctor (doctor_name, speciality, department_id) VALUES ('$name', '$special', '$dept_id')";

            if (mysqli_query($conn, $sql)) {
                echo "Doctor {$name} added successfully to the {$dept_name} department.";
            } else {
                echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style='color:red;'>Error: Department '$dept_name' not found.</p>";
        }
    } else {
        echo "<p style='color:red;'>$err</p>";
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>