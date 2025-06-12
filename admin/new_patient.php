<?php
include("../database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Patients</title>
</head>
<body>
    <h1>Make entries for a new patient</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Patient's name:<br>
        <input type="text" name="name"><br>
        Age:<br>
        <input type="number" name="age"><br>
        Date of birth:<br>
        <input type="date" name="dob"><br>
        Sex:<br>
        <select name="sex">
            <option value="">Select Sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>
        Patient's contact number:<br>
        <input type="text" name="contact"><br>
        Patient's Email address:<br>
        <input type="email" name="email"><br>
        Identification proof submitted by the patient:<br>
        <input type="text" name="id_submitted"><br>
        Any escort with the patient?(Leave the escort details blank if none)<br>
        <input type="radio" name="escort" value="1" required> Yes<br>
        <input type="radio" name="escort" value="0"> No<br>
        Escort's name:<br>
        <input type="text" name="escort_name"><br>
        Escort's relation with the patient(or vice versa):<br>
        <input type="text" name="relation"><br>
        Escort's number:<br>
        <input type="number" name="escort_num"><br>
        Patient's residential address:<br>
        <input type="text" name="address"><br>
        Any significant medical history of the patient?<br>
        <input type="text" name="med_his"><br>
        <input type="submit" class="submit-btn" name="submit" value="Submit"><br>
    </form>
</body>
</html>

<?php
$name = $age = $sex = $contact = $email = $address = $med_his = "";
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST["name"]);
    $age = trim($_POST["age"]);
    $dob = trim($_POST["dob"]);
    $sex = trim($_POST["sex"]);
    $contact = trim($_POST["contact"]);
    $email = trim($_POST["email"]);
    $id_submitted = trim($_POST["id_submitted"]);
    $escort = isset($_POST["escort"]) ? $_POST["escort"] : null;
    $escort_name = trim($_POST["escort_name"]);
    $relation = trim($_POST["relation"]);
    $escort_num = trim($_POST["escort_num"]);
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
    elseif ($escort === null) {
        $err = "Please select if an escort is with the patient";
    }


    if (!$err) {
        $name = mysqli_real_escape_string($conn, $name);
        $age = mysqli_real_escape_string($conn, $age);
        $dob = mysqli_real_escape_string($conn, $dob);
        $sex = mysqli_real_escape_string($conn, $sex);
        $contact = mysqli_real_escape_string($conn, $contact);
        $email = mysqli_real_escape_string($conn, $email);
        $id_submitted = mysqli_real_escape_string($conn, $id_submitted);
        $escort = (int)$escort;
        $escort_name = mysqli_real_escape_string($conn, $escort_name);
        $relation = mysqli_real_escape_string($conn, $relation);
        $escort_num = mysqli_real_escape_string($conn, $escort_num);
        $address = mysqli_real_escape_string($conn, $address);
        $med_his = mysqli_real_escape_string($conn, $med_his);

        $sql = "INSERT INTO patient (patient_name, age, dob, sex, contact_no, email_id, id_submitted, arrive_w_escort, escort_name, relation_w_escort, escort_number, residential_add, medical_history)
                VALUES ('$name', '$age', '$dob', '$sex', '$contact', '$email', '$id_submitted', '$escort', '$escort_name', '$relation', '$escort_num', '$address', '$med_his')";

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <br>
    <a href="home_admin.php" class="go-home-btn">Go home</a>
</body>
</html>