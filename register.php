<?php
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Register</h1>
        Enter your full name:<br>
        <input type="text" name="full_name" required><br>
        Register as:<br>
        <select name="user_type" required>
            <option value="">User type</option>
            <option value="admin">Admin</option>
            <option value="doctor">Doctor</option>
            <option value="nurse">Nurse</option>
            <option value="pharmacy">Pharmacy</option>
            <option value="reception">Reception</option>
            <option value="lab">Lab</option>
            <option value="billing">Billing</option>
        </select><br>
        Enter your email address:<br>
        <input type="email" name="email_id" required><br>
        Enter your contact number:<br>
        <input type="number" name="contact_number" required><br>
        Make a Username:<br>
        <input type="text" name="username" required><br>
        Password:<br>
        <input type="password" name="password" id="password" required oninput="checkStrength()" style="width: 200px;">
        <button type="button" onclick="togglePassword()" style="margin-left: 8px; cursor: pointer; background: none; border: none; color: grey; font-size: 18px;">
            👁️
        </button>
        <div id="strength" style="margin-top: 5px; font-size: 14px;"></div>
        <br>
        <input type="submit" name="submit" value="Register"><br>
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
    </form>
</body>

</html>

<?php
$full_name = $user_type = $email = $contact_number = $username = $password = "";
$err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $user_type = trim($_POST["user_type"]);
    $email_id = trim($_POST["email_id"]);
    $contact_number = trim($_POST["contact_number"]);
    $username = trim($_POST["username"]);
    $raw_password = trim($_POST["password"]);

    if (empty($full_name)) {
        $err = "Please enter your full_name";
    } elseif (empty($user_type)) {
        $err = "Please specify your user type";
    } elseif (!empty($email_id) && !filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
        $err = "Please enter a valid email address";
    } elseif (empty($contact_number) || !preg_match('/^\d+$/', $contact_number)) {
        $err = "Please enter a valid contact number";
    } elseif (empty($username)) {
        $err = "Please make a username";
    }
    if (empty($raw_password)) {
        $err = "Please make a password";
    } else {
        $password = password_hash($raw_password, PASSWORD_DEFAULT);
    }

    if (!$err)
    {
        $full_name = mysqli_real_escape_string($conn, $full_name);
        $user_type = mysqli_real_escape_string($conn, $user_type);
        $email_id = mysqli_real_escape_string($conn, $email_id);
        $contact_number = mysqli_real_escape_string($conn, $contact_number);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        $check_sql = "SELECT * FROM user WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            $err = "Username already exists. Please choose another.";
        } else {
            $sql = "INSERT INTO user (username, password, full_name, email_id, contact_number, user_type)
                    VALUES ('$username', '$password', '$full_name', '$email_id', '$contact_number', '$user_type')";

            if (mysqli_query($conn, $sql)) {
                header("Location: login.php");
                exit();
            } else {
                echo "<p class='error-message'style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
            }
        }
    }
    else {
        echo "<p class='error-message'style='color:red;'>$err</p>";
    }
}
?>