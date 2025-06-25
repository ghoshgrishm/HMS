<?php
include("database.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Register</h1>
        Enter your full name:<br>
        <input type="text" name="full_name" required><br>

        Register as:<br>
        <select name="user_type" id="user_type" required>
            <option value="">User type</option>
            <option value="admin">Admin</option>
            <option value="doctor">Doctor</option>
            <option value="nurse">Nurse</option>
            <option value="pharmacy">Pharmacy</option>
            <option value="reception">Reception</option>
            <option value="lab">Lab</option>
            <option value="billing">Billing</option>
        </select><br>

        <div id="departmentField" style="display:none;">
            Select Department:<br>
            <select name="department_id">
                <option value="">Select Department</option>
                <option value="1">Emergency</option>
                <option value="2">Medicine</option>
                <option value="3">Surgery</option>
                <option value="4">Gynecology & Obstetrics</option>
                <option value="5">Ophthalmology</option>
                <option value="6">ENT</option>
                <option value="7">Pediatrics</option>
                <option value="8">Orthopaedics</option>
                <option value="9">ICU(Intensive Care Unit)</option>
                <option value="10">Caridology</option>
                <option value="11">Neurology</option>
                <option value="12">General Medicine</option>
            </select><br>
        </div>

        Enter your email address:<br>
        <input type="email" name="email_id" required><br>

        Enter your contact number:<br>
        <input type="number" name="contact_number" required><br>

        Make a Username:<br>
        <input type="text" name="username" required><br>

        Password:<br>
        <input type="password" name="password" id="password" required oninput="checkStrength()" style="width: 200px;">
        <button type="button" onclick="togglePassword()" style="margin-left: 8px;">👁️</button>
        <div id="strength" style="margin-top: 5px; font-size: 14px;"></div><br>

        <input type="submit" name="submit" value="Register"><br>
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

            let message = '', color = '';
            if (strength <= 2) {
                message = 'Weak'; color = 'red';
            } else if (strength <= 4) {
                message = 'Medium'; color = 'orange';
            } else {
                message = 'Strong'; color = 'green';
            }

            strengthDiv.textContent = 'Strength: ' + message;
            strengthDiv.style.color = color;
        }

        document.getElementById('user_type').addEventListener('change', function () {
            document.getElementById('departmentField').style.display =
                this.value === 'doctor' ? 'block' : 'none';
        });
    </script>
</body>
</html>

<?php
$full_name = $user_type = $email_id = $contact_number = $username = $password = "";
$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $user_type = trim($_POST["user_type"]);
    $email_id = trim($_POST["email_id"]);
    $contact_number = trim($_POST["contact_number"]);
    $username = trim($_POST["username"]);
    $raw_password = trim($_POST["password"]);
    $department_id = isset($_POST["department_id"]) ? trim($_POST["department_id"]) : "";

    if (empty($full_name)) {
        $err = "Please enter your full name.";
    } elseif (empty($user_type)) {
        $err = "Please specify your user type.";
    } elseif (!empty($email_id) && !filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
        $err = "Please enter a valid email address.";
    } elseif (empty($contact_number) || !preg_match('/^\d+$/', $contact_number)) {
        $err = "Please enter a valid contact number.";
    } elseif (empty($username)) {
        $err = "Please choose a username.";
    } elseif (empty($raw_password)) {
        $err = "Please enter a password.";
    } else {
        $password = password_hash($raw_password, PASSWORD_DEFAULT);
    }

    $doctor_id = "NULL";

    if (!$err && $user_type === "doctor") {
        if (empty($department_id)) {
            $err = "Please select your department.";
        } else {
            $full_name_esc = mysqli_real_escape_string($conn, $full_name);
            $department_id_esc = mysqli_real_escape_string($conn, $department_id);
            $doctor_query = "SELECT doctor_id FROM doctor WHERE doctor_name = '$full_name_esc' AND department_id = '$department_id_esc'";
            $doctor_result = mysqli_query($conn, $doctor_query);

            if (mysqli_num_rows($doctor_result) == 0) {
                $err = "No matching doctor found in hospital records.";
            } else {
                $doctor_row = mysqli_fetch_assoc($doctor_result);
                $doctor_id = $doctor_row['doctor_id'];
            }
        }
    }

    if (!$err) {
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
            if ($user_type === "doctor") {
                $sql = "INSERT INTO user (username, password, full_name, email_id, contact_number, user_type, doctor_id)
                        VALUES ('$username', '$password', '$full_name', '$email_id', '$contact_number', '$user_type', '$doctor_id')";
            } else {
                $sql = "INSERT INTO user (username, password, full_name, email_id, contact_number, user_type)
                        VALUES ('$username', '$password', '$full_name', '$email_id', '$contact_number', '$user_type')";
            }

            if (mysqli_query($conn, $sql)) {
                header("Location: login.php");
                exit();
            } else {
                echo "<p class='error-message' style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
            }
        }
    } else {
        echo "<p class='error-message' style='color:red;'>$err</p>";
    }
}
?>
