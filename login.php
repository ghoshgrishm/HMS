<?php
include("database.php");
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
        <h1>Login</h1>
        Enter your Username:<br>
        <input type="text" name="username" required><br>
        Enter your Password:<br>
        <input type="password" name="password" required><br>
        <input type="submit" name="submit" value="Login"><br>
    </form> 
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $err="";
    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        header("Location: home_doctor.php");
        exit();
    }
    else
    {
        $err = "Invalid username or password.";
        echo "<p style='color:red;'>$err</p>";
    }
    $searchPerformed = true;
    mysqli_close($conn);
}
?>