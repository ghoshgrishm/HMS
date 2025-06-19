<?php
include("../database.php");

$searchResults = [];
$searchPerformed = false;
$patientDetails = null;
$patient_id = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['search'])) {
        $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
        $sql = "SELECT * FROM patient WHERE LOWER(patient_name) LIKE LOWER('%$name%')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $searchResults = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        $searchPerformed = true;
    }

    if (isset($_POST['view_summary'])) {
        $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);

        // Fetch patient details
        $patient_sql = "SELECT * FROM patient WHERE patient_id = '$patient_id'";
        $res = mysqli_query($conn, $patient_sql);
        $patientDetails = $res ? mysqli_fetch_assoc($res) : null;

        // Fetch related counts
        $admission_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM admission WHERE patient_id = '$patient_id'"));
        $appointment_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointment WHERE patient_id = '$patient_id'"));
        $test_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM test WHERE admission_id IN (SELECT admission_id FROM admission WHERE patient_id = '$patient_id') OR appointment_id IN (SELECT appointment_id FROM appointment WHERE patient_id = '$patient_id')"));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Summary</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .card {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            cursor: pointer;
            background-color: #f9f9f9;
        }
        .card:hover {
            background-color: #e6f7ff;
        }
    </style>
</head>
<body>
<h1>Search Patient</h1>
<form method="post">
    <input type="text" name="patient_name" required placeholder="Enter Patient Name">
    <button type="submit" name="search">Search</button>
</form>

<?php if ($searchPerformed): ?>
    <h2>Matching Patients</h2>
    <?php if (!empty($searchResults)): ?>
        <?php foreach ($searchResults as $patient): ?>
            <form method="post" class="card">
                <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>">
                <p><strong><?= htmlspecialchars($patient['patient_name']) ?></strong><br>
                   Age: <?= $patient['age'] ?> | Sex: <?= $patient['sex'] ?><br>
                   Contact: <?= $patient['contact_no'] ?>
                </p>
                <button type="submit" name="view_summary">View Full Summary</button>
            </form>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="error-message">No matching patients found.</p>
    <?php endif; ?>
<?php endif; ?>

<?php if ($patientDetails): ?>
    <h2>Patient Details</h2>
    <p><strong>ID:</strong> <?= $patientDetails['patient_id'] ?></p>
    <p><strong>Name:</strong> <?= $patientDetails['patient_name'] ?></p>
    <p><strong>Age:</strong> <?= $patientDetails['age'] ?> | <strong>DOB:</strong> <?= $patientDetails['dob'] ?></p>
    <p><strong>Sex:</strong> <?= $patientDetails['sex'] ?> | <strong>Contact:</strong> <?= $patientDetails['contact_no'] ?></p>
    <p><strong>Email:</strong> <?= $patientDetails['email_id'] ?> | <strong>ID Proof:</strong> <?= $patientDetails['id_submitted'] ?></p>
    <p><strong>Address:</strong> <?= $patientDetails['residential_add'] ?></p>
    <p><strong>Medical History:</strong> <?= $patientDetails['medical_history'] ?></p>

    <h3>Statistics</h3>
    <p><strong>Number of times admitted:</strong> <?= $admission_count ?></p>
    <p><strong>Number of appointments booked:</strong> <?= $appointment_count ?></p>
    <p><strong>Tests done:</strong> <?= $test_count ?></p>

    <form action="patient_full_summary.php" method="post">
        <input type="hidden" name="patient_id" value="<?= $patientDetails['patient_id'] ?>">
        <button type="submit">View Detailed Summary</button>
    </form>
<?php endif; ?>

<br><a href="home_nurse.php" class="go-home-btn">Go home</a>
</body>
</html>
