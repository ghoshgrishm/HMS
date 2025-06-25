<?php
include("database.php");
$name = $age = $dob = $sex = $contact = $email = "";
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $age = trim($_POST["age"]);
    $dob = trim($_POST["dob"]);
    $sex = trim($_POST["sex"]);
    $contact = trim($_POST["contact"]);
    $email = trim($_POST["email"]);

    if (empty($name)) {
        $err = "Please enter the patient's name";
    } elseif (empty($age) || !is_numeric($age)) {
        $err = "Please enter a valid patient's age";
    } elseif (empty($sex)) {
        $err = "Please enter the patient's sex";
    } elseif (empty($contact) || !preg_match('/^\d+$/', $contact)) {
        $err = "Please enter a valid contact number";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Please enter a valid email address";
    }

    if (!$err) {
        $name = mysqli_real_escape_string($conn, $name);
        $age = mysqli_real_escape_string($conn, $age);
        $dob = mysqli_real_escape_string($conn, $dob);
        $sex = mysqli_real_escape_string($conn, $sex);
        $contact = mysqli_real_escape_string($conn, $contact);
        $email = mysqli_real_escape_string($conn, $email);

        $check_sql = "SELECT * FROM patient WHERE LOWER(patient_name) LIKE LOWER('$name') AND (contact_no = '$contact' OR email_id = '$email')";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<div style='background:#ffeeee; padding:10px; border:1px solid red; margin-top: 20px;'>";
            echo "<p style='color:red; font-weight:bold;'>Are you sure you have not visited the hospital before?</p>";
            echo "<p>If yes, please <a href='visited.php' class='go-home-btn'>Go home</a>.</p>";
            echo "</div>";
        } else {
            // Insert new patient
            $sql = "INSERT INTO patient (patient_name, age, dob, sex, contact_no, email_id)
                    VALUES ('$name', '$age', '$dob', '$sex', '$contact', '$email')";

            if (mysqli_query($conn, $sql)) {
                $new_id = mysqli_insert_id($conn);
                header("Location: new_appointment.php?patient_id=$new_id");
                exit();
            } else {
                echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
            }
        }
    } else {
        echo "<p class='error-message'style='color:red;'>$err</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Patient</title>
</head>
<body>
    <h1>New Patient Registration</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Your name:<br>
        <input type="text" name="name" required><br>
        Your age:<br>
        <input type="number" name="age" required><br>
        Your date of birth:<br>
        <input type="date" name="dob" required><br>
        Your sex:<br>
        <select name="sex" required>
            <option value="">Select Sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>
        Your contact number:<br>
        <input type="text" name="contact" required><br>
        Your email address:<br>
        <input type="email" name="email" required><br>
        <input type="submit" class="submit-btn" name="submit" value="Submit"><br>
    </form>
</body>
</html>
