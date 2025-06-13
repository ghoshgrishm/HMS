<?php
session_start();


include("database.php");

$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $username = mysqli_real_escape_string($conn, $username);
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $user_type = $row['user_type'];
            $_SESSION["username"] = $username;
            $_SESSION["user_type"] = $user_type;
            $_SESSION["user_id"] = $row['user_id'];

            switch ($user_type) {
                case 'admin':
                    header("Location: admin/home_admin.php");
                    exit();
                case 'doctor':
                    header("Location: doctor/home_doctor.php");
                    exit();
                case 'nurse':
                    header("Location: nurse/home_nurse.php");
                    exit();
                case 'reception':
                    header("Location: reception/home_reception.php");
                    exit();
                case 'lab':
                    header("Location: lab/home_lab.php");
                    exit();
                case 'billing':
                    header("Location: billing/home_billing.php");
                    exit();
                default:
                    $err = "Unknown user type: $user_type";
            }
        } else {
            $err = "Invalid username or password.";
        }
    } else {
        $err = "Invalid username or password.";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Sinbadh Hospitals</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Login</h1>
        Username:<br>
        <input type="text" name="username" placeholder="Username" required><br>
        Password:<br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="submit" name="submit" value="Login"><br>
    </form>
    <?php
    if (!empty($err)) {
        echo "<p style='color:red;'>$err</p>";
    }
    ?>
    <h2>Not registered yet?</h2>
    <a href="register.php" class="go-home-btn">Register</a>
</body>
</html>
