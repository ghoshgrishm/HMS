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
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $err = "";

    $username = mysqli_real_escape_string($conn, $username);

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $user_type = $row['user_type'];

            switch ($user_type) {
                case 'Admin':
                    header("Location: home_admin.php");
                    break;
                case 'Doctor':
                    header("Location: home_doctor.php");
                    break;
                case 'Nurse':
                    header("Location: home_nurse.php");
                    break;
                case 'Reception':
                    header("Location: home_reception.php");
                    break;
                case 'Lab':
                    header("Location: home_lab.php");
                    break;
                case 'Accountant':
                    header("Location: home_accountant.php");
                    break;
                default:
                    echo "<p style='color:red;'>Unknown user type.</p>";
                    exit();
            }
            exit();
        } else {
            $err = "Invalid username or password.";
        }
    } else {
        $err = "Invalid username or password.";
    }

    if (!empty($err)) {
        echo "<p style='color:red;'>$err</p>";
    }

    mysqli_close($conn);
}
?>
