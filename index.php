<?php
include("database.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospital = $_POST["hospital"];
    if ($hospital == "register") {
        header("Location: register.php");
        exit();
    } elseif ($hospital == "login") {
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Sinbadh Hospitals</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Welcome to Sinbadh Hospitals</h1>
        <button type="submit" name="hospital" value="register" class="btn">Register</button><br>
        <button type="submit" name="hospital" value="login" class="btn">Login</button><br>
    </form>
</body>
</html>
