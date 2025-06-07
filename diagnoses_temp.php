<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">    
        <label>Find Diagnoses from OPD or IPD:</label><br>
        OPD:
        <input type="radio" name="diagnosis_type" value="opd" required><br>
        IPD:
        <input type="radio" name="diagnosis_type" value="ipd" required><br>
        <input type="submit" name="submit" value="submit"><br>
    </form>
</body>
</html>
    
<?php
    if ($_SERVER["REQUEST_METHOD"]=="POST")
    {
        $diagnosis_type = $_POST["diagnosis_type"];
        if ($diagnosis_type=="opd")
        {
            header("Location: check_diagnoses_opd.php");
            exit();
        }
        elseif($diagnosis_type=="ipd")
        {
            header("Location: check_diagnoses_ipd.php");
            exit();
        }
        else{
            echo "<p style='color:red;'>Please select an option</p>";
        }
    }
?>