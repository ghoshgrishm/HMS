<?php
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>New Admission</title>
    <style>
        .check-admissions-btn{
            padding: 8px 15px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            background-color: #2196F3;
            margin-top: 10px;
        }
        .check-admissions-btn:hover{
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Please enter the patient's name:<br>
        <input type="text" name="name"><br>
        Please enter the patient's current condition:<br>
        <input type="text" name="condition"><br>
        Please enter the department name:<br>
        <input type="text" name="dept"><br>
        Please enter the bed number:<br>
        <input type="number" name="bed"><br>
        Enter the predicted discharge date:<br>
        <input type="date" name="discharge"><br>
        <input type="submit" name="submit" value="Submit"><br>
    </form>
</body>
</html>

<?php
$name = $condition = $dept = $bed = $discharge = "";
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $condition = trim($_POST["condition"]);
    $dept = trim($_POST["dept"]);
    $bed = trim($_POST["bed"]);
    $discharge = trim($_POST["discharge"]);

    if (empty($name)) {
        $err = "Please enter the patient's name";
    } elseif (empty($condition)) {
        $err = "Please enter the patient's condition";
    }elseif (empty($dept)) {
        $err = "Please enter the department name";
    } elseif (empty($bed)) {
        $err = "Please enter the assigned bed number";
    } elseif (empty($discharge)) {
        $err = "Please enter the assumed discharge date";
    }

    if (!$err) {
        $name = mysqli_real_escape_string($conn, $name);
        $condition = mysqli_real_escape_string($conn, $condition);
        $dept = mysqli_real_escape_string($conn, $dept);
        $bed = mysqli_real_escape_string($conn, $bed);
        $discharge = mysqli_real_escape_string($conn, $discharge);

        $sql_patient = "SELECT patient_id FROM patient WHERE name = '$name'";
        $result_patient = mysqli_query($conn, $sql_patient);

        $sql_dept = "SELECT department_id FROM department WHERE dept_name = '$dept'";
        $result_dept = mysqli_query($conn, $sql_dept);

        $sql_bed = "SELECT bed_id, status FROM bed WHERE bed_number = '$bed'";
        $result_bed = mysqli_query($conn, $sql_bed);

        if (mysqli_num_rows($result_patient) > 0 && mysqli_num_rows($result_dept) > 0 && mysqli_num_rows($result_bed) > 0) {
            $patient_id = mysqli_fetch_assoc($result_patient)['patient_id'];
            $department_id = mysqli_fetch_assoc($result_dept)['department_id'];
            $bed_row = mysqli_fetch_assoc($result_bed);

            if (strtolower($bed_row['status']) === 'available') {
                $bed_id = $bed_row['bed_id'];

                $sql = "INSERT INTO admission (patient_id, patient_condition, department_id, bed_id, predict_discharge)
                        VALUES ('$patient_id', '$condition', '$department_id', '$bed_id', '$discharge')";
                
                if (mysqli_query($conn, $sql)) {
                    $update_bed = "UPDATE bed SET bed_status = 'occupied' WHERE bed_id = '$bed_id'";
                    mysqli_query($conn, $update_bed);

                    echo "<p style='color:green;'>New admission successfully made.</p>";
                } else {
                    echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
                }
            } else {
                echo "<p style='color:red;'>Bed $bed is currently occupied. Please choose a different bed.</p>";
            }
        } else {
            echo "<p style='color:red;'>Invalid patient, department, or bed number provided.</p>";
        }
    } else {
        echo "<p style='color:red;'>$err</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Back</title>
</head>
<body>
    <a href="check_admissions.php" class="check-admissions-btn">Check Admissions</a>
    <br>
    <a href="index.php" class="go-home-btn">Go home</a>
</body>
</html>
