<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("database.php");
session_start();

$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $raw_password = trim($_POST["password"]);

    if (empty($username)) {
        $err = "Please enter your username";
    } elseif (empty($raw_password)) {
        $err = "Please make a password";
    }

    if (empty($err)) {
        $password = password_hash($raw_password, PASSWORD_DEFAULT);

        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        $check_sql = "SELECT * FROM user WHERE username = '$username'";
        $result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($result) > 0) {
            $sql = "UPDATE user SET password = '$password' WHERE username = '$username'";
            if (mysqli_query($conn, $sql)) {
                header("Location: login.php");
                exit();
            } else {
                $err = "Error updating password: " . mysqli_error($conn);
            }
        } else {
            $err = "No such username exists";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <h1>Reset Password</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Username:<br>
        <input type="text" name="username" placeholder="Username" required><br>    

        Enter your new password:<br>
        <input type="password" name="password" id="password" required oninput="checkStrength()" style="width: 200px;">
        <button type="button" onclick="togglePassword()" style="margin-left: 8px; cursor: pointer; background: none; border: none; color: grey; font-size: 18px;">
            👁️
        </button>
        <div id="strength" style="margin-top: 5px; font-size: 14px;"></div>
        <br>

        <input type="submit" name="submit" value="Submit"><br>

        <?php if (!empty($err)) echo "<p class='error-message'style='color:red;'>$err</p>"; ?>
    </form>

    <script>
        function togglePassword() {
            const field = document.getElementById("password");
            field.type = field.type === "password" ? "text" : "password";
        }

        function checkStrength() {
            const strengthDiv = document.getElementById("strength");
            const password = document.getElementById("password").value;

            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            let message = '';
            let color = '';

            if (strength <= 2) {
                message = 'Weak';
                color = 'red';
            } else if (strength === 3 || strength === 4) {
                message = 'Medium';
                color = 'orange';
            } else {
                message = 'Strong';
                color = 'green';
            }

            strengthDiv.textContent = 'Strength: ' + message;
            strengthDiv.style.color = color;
        }
    </script>
</body>
</html>
