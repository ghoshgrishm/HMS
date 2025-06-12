<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit type Selection</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <p style='color:green;'>Patient added successfully.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">    
        <label>Reason for Visit:</label><br>
        Appointment
        <input type="radio" name="visit_type" value="appointment" required><br>
        Emergency
        <input type="radio" name="visit_type" value="emergency" required><br>
        <input type="submit" name="submit" value="submit"><br>
    </form>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
        $visit_type = $_POST["visit_type"];
        if ($visit_type == "appointment") {
            header("Location: new_appointment.php");
            exit();
        }
        elseif ($visit_type = "emergency") {
            header("Location: new_admission.php");
            exit();
        }
        else{
            echo "<p class='error-message'>Please select a visit type.</p>";
        }
    }
?>