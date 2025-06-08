<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // For now: no validation
    header("Location: home_doctor.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sinbadh Hospitals</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Enter your Username:<br>
        <input type="text" name="username" required><br>
        Enter your Password:<br>
        <input type="password" name="password" required><br>
        <input type="submit" name="submit" value="Login"><br>
    </form>
</body>
</html>
