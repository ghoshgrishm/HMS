<?php
include("../database.php");

$step = 1;
$selected_patient = $selected_doctor = $selected_slot = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['select_patient'])) {
        $selected_patient = $_POST['select_patient'];
        $step = 2;
    } elseif (isset($_POST['select_department'])) {
        $selected_patient = $_POST['selected_patient'];
        $selected_department = $_POST['select_department'];
        $step = 3;
    } elseif (isset($_POST['select_doctor'])) {
        $selected_patient = $_POST['selected_patient'];
        $selected_doctor = $_POST['select_doctor'];
        $step = 4;
    } elseif (isset($_POST['select_slot'])) {
        $selected_patient = $_POST['selected_patient'];
        $selected_doctor = $_POST['selected_doctor'];
        $selected_slot = $_POST['select_slot'];

        // Get department_id from doctor
        $result = mysqli_query($conn, "SELECT department_id FROM doctor WHERE doctor_id = $selected_doctor");
        $doctor_row = mysqli_fetch_assoc($result);
        $department_id = $doctor_row['department_id'];

        // Insert into appointment table
        $today = date("Y-m-d");
        $token = rand(1000, 9999);
        mysqli_query($conn, "INSERT INTO appointment (patient_id, doctor_id, department_id, appointment_date, appointment_slot, Token_number)
                            VALUES ($selected_patient, $selected_doctor, $department_id, '$today', '$selected_slot', $token)");

        // Update doctor's slot to 'unavailable'
        mysqli_query($conn, "UPDATE doctor SET `$selected_slot` = 'booked' WHERE doctor_id = $selected_doctor");

        echo "<h3>✅ Appointment Booked Successfully!</h3>";
        exit;
    }
}
?>

<h1>Book an Appointment</h1>

<!-- STEP 1: SEARCH PATIENT -->
<?php if ($step == 1): ?>
<form method="post">
    <label>Search Patient Name:</label>
    <input type="text" name="patient_name" required>
    <button type="submit" name="search_patient">Search</button>
</form>

<?php
if (isset($_POST['search_patient'])) {
    $name = mysqli_real_escape_string($conn, $_POST['patient_name']);
    $res = mysqli_query($conn, "SELECT * FROM patient WHERE patient_name LIKE '%$name%'");
    if (mysqli_num_rows($res)>0) 
    {
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<form method='post'>
                <input type='hidden' name='select_patient' value='{$row['patient_id']}'>
                <button type='submit'>
                    {$row['patient_name']} <br> Age: {$row['age']} <br> DOB: {$row['dob']} <br> Sex: {$row['sex']}
                </button>
            </form>";
        }
    }
    else {
        echo "<p class='error-message'>No patient record found matching your entry</p>";
    }
}
endif;
?>

<!-- STEP 2: SELECT DEPARTMENT -->
<?php if ($step == 2): ?>
    <h3>Select Department</h3>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM department");
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<form method='post'>
            <input type='hidden' name='selected_patient' value='$selected_patient'>
            <input type='hidden' name='select_department' value='{$row['department_id']}'>
            <button type='submit'>{$row['dept_name']}</button>
        </form>";
    }
    ?>
<?php endif; ?>

<!-- STEP 3: SELECT DOCTOR -->
<?php if ($step == 3): ?>
    <h3>Select Doctor</h3>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM doctor WHERE department_id = $selected_department");
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<form method='post'>
            <input type='hidden' name='selected_patient' value='$selected_patient'>
            <input type='hidden' name='select_doctor' value='{$row['doctor_id']}'>
            <button type='submit'>Dr. {$row['doctor_name']} (ID: {$row['doctor_id']})</button>
        </form>";
    }
    ?>
<?php endif; ?>

<!-- STEP 4: SELECT SLOT -->
<?php if ($step == 4): ?>
    <h3>Select Slot</h3>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM doctor WHERE doctor_id = $selected_doctor");
    $row = mysqli_fetch_assoc($res);
    $slots = [
        "eight_to_nine" => "8:00 AM - 9:00 AM",
        "nine_to_ten" => "9:00 AM - 10:00 AM",
        "ten_to_eleven" => "10:00 AM - 11:00 AM",
        "eleven_to_twelve" => "11:00 AM - 12:00 PM",
        "twelve_to_one" => "12:00 PM - 1:00 PM",
        "one_to_two" => "1:00 PM - 2:00 PM",
        "two_to_three" => "2:00 PM - 3:00 PM",
        "three_to_four" => "3:00 PM - 4:00 PM",
        "four_to_five" => "4:00 PM - 5:00 PM",
        "five_to_six" => "5:00 PM - 7:00 PM",
        "six_to_seven" => "6:00 PM - 7:00 PM",
        "seven_to_eight" => "7:00 PM - 8:00 PM",
        "eight_to_nine_pm" => "8:00 PM - 9:00 PM",
        "nine_to_ten_pm" => "9:00 PM - 10:00 PM"
    ];
    foreach ($slots as $key => $label) {
        if ($row[$key] == "available") {
            echo "<form method='post' style='display:inline-block; margin: 4px'>
                <input type='hidden' name='selected_patient' value='$selected_patient'>
                <input type='hidden' name='selected_doctor' value='$selected_doctor'>
                <input type='hidden' name='select_slot' value='$key'>
                <button type='submit'>$label</button>
            </form>";
        }
    }
    ?>
<?php endif; ?>
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
    <a href="home_nurse.php" class="go-home-btn">Go home</a>
</body>
</html>
