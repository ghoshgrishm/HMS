<?php
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Register</h1>
        Enter your full name:<br>
        <input type="text" name="full_name" required><br>
        Register as:<br>
        <select name="user_type" required>
            <option value="">User type</option>
            <option value="Male">Doctor</option>
            <option value="Female">Department</option>
            <option value="Other">Lab</option>
        </select><br>
        Enter your email address:<br>
        <input type="email" name="email_id" required><br>
        Enter your contact number:<br>
        <input type="number" name="contact_number" required><br>
        Make a Username:<br>
        <input type="text" name="username" required><br>
        Make a Password:<br>
        <input type="password" name="password" required><br>
        <input type="submit" name="submit" value="Login"><br>
    </form>
</body>
</html>

<?php
    $full_name = $user_type = $email = $contact_number = $username = $password = "";
    $err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $full_name = trim($_POST["full_name"]);
    $user_type = trim($_POST["user_type"]);
    $email_id = trim($_POST["email_id"]);
    $contact_number = trim($_POST["contact_number"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($full_name)) {
        $err = "Please enter your full_name";
    }
    elseif (empty($user_type)) {
        $err = "Please specify your user type";
    }
    elseif (!empty($email_id) && !filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
        $err = "Please enter a valid email address";
    }
    elseif (empty($contact_number) || !preg_match('/^\d+$/', $contact_number)) {
        $err = "Please enter a valid contact number";
    }
    elseif (empty($username)) {
        $err = "Please make a username";
    }
    elseif (empty($password)) {
        $err = "Please make a password";
    }

    if (!$err) {
        $full_name = mysqli_real_escape_string($conn, $full_name);
        $user_type = mysqli_real_escape_string($conn, $user_type);
        $email_id = mysqli_real_escape_string($conn, $email_id);
        $contact_number = mysqli_real_escape_string($conn, $contact_number);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        $sql = "INSERT INTO patient(username, password, full_name, email_id, contact_number, user_type)
                VALUES ('$username', '$password', '$full_name', '$email_id', '$contact_number', '$user_type')";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>$err</p>";
    }
}
?>
?>