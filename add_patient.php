<?php
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Patients</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Please enter the patient's name:<br>
        <input type="text" name="name"><br>
        Please enter the patient's age:<br>
        <input type="number" name="age"><br>
        Please enter the patient's sex:<br>
        <select name="sex">
            <option value="">Select Sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>
        Please provide your contact number:<br>
        <input type="text" name="contact"><br>
        Please provide your Email address:<br>
        <input type="email" name="email"><br>
        Please provide the patient's residential address:<br>
        <input type="text" name="address"><br>
        Any significant medical history of the patient:<br>
        <input type="text" name="med_his"><br>
        <input type="submit" name="submit" value="Submit"><br>
    </form>
</body>
</html>

<?php
$name = $age = $sex = $contact = $email = $address = $med_his = "";
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST["name"]);
    $age = trim($_POST["age"]);
    $sex = trim($_POST["sex"]);
    $contact = trim($_POST["contact"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);
    $med_his = trim($_POST["med_his"]);

    if (empty($name)) {
        $err = "Please enter the patient's name";
    }
    elseif (empty($age) || !is_numeric($age)) {
        $err = "Please enter a valid patient's age";
    }
    elseif (empty($sex)) {
        $err = "Please enter the patient's sex";
    }
    elseif (empty($contact) || !preg_match('/^\d+$/', $contact)) {
        $err = "Please enter a valid contact number";
    }
    elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Please enter a valid email address";
    }

    if (!$err) {
        $name = mysqli_real_escape_string($conn, $name);
        $age = mysqli_real_escape_string($conn, $age);
        $sex = mysqli_real_escape_string($conn, $sex);
        $contact = mysqli_real_escape_string($conn, $contact);
        $email = mysqli_real_escape_string($conn, $email);
        $address = mysqli_real_escape_string($conn, $address);
        $med_his = mysqli_real_escape_string($conn, $med_his);

        $sql = "INSERT INTO patient (name, age, sex, contact_no, email_id, residential_add, medical_history)
                VALUES ('$name', '$age', '$sex', '$contact', '$email', '$address', '$med_his')";

        if (mysqli_query($conn, $sql)) {
            header("Location: visit.php");
            exit();
        } else {
            echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>$err</p>";
    }
}
?>