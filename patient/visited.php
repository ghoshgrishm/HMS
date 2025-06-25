<?php
include("../database.php");

$patients = [];
$searchPerformed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["patient_name"]);
    $contact = mysqli_real_escape_string($conn, $_POST["contact_no"]);
    $email = mysqli_real_escape_string($conn, $_POST["email_id"]);

    $sql = "SELECT * FROM patient 
            WHERE LOWER(patient_name) LIKE LOWER('%$name%') 
              AND contact_no = '$contact' 
              AND email_id = '$email'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $patients[] = $row;
        }
    }

    $searchPerformed = true;
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Visited</title>
  <link rel="stylesheet" href="../styles/styles.css">
  <style>
    .card {
      border: 2px solid #ffffff;
      border-radius: 10px;
      padding: 15px;
      margin: 10px 0;
      background-color: #f9f9f9;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    .card:hover {
      background-color: #e6f0ff;
    }
  </style>
  <script>
    function selectPatient(patient) {
      // You can pass ID or more data via query string
      window.location.href = `check_requests.php?patient_id=` + encodeURIComponent(patient.patient_id);
    }
  </script>
</head>
<body>
  <h1>Registered Patient</h1>
  
  <form method="post">
        <label for="patient_name">Enter your full name:</label><br>
        <input type="text" id="patient_name" name="patient_name" required><br>
        <label for="contact_no">Enter your contact number:</label><br>
        <input type="text" id="contact_no" name="contact_no" required><br>
        <label for="email_id">Enter your email address:</label><br>
        <input type="email" id="email_id" name="email_id" required><br>
        <button type="submit">Search</button>
    </form>


  <?php if ($searchPerformed): ?>
    <h2>Search Results</h2>
    <?php if (!empty($patients)): ?>
      <h3>Click a patient to continue:</h3>
      <?php foreach ($patients as $p): ?>
        <div class="card" onclick='selectPatient(<?= json_encode($p) ?>)'>
          <strong>ID:</strong> <?= $p['patient_id'] ?><br>
          <strong>Name:</strong> <?= $p['patient_name'] ?><br>
          <strong>Age:</strong> <?= $p['age'] ?><br>
          <strong>DOB:</strong> <?= $p['dob'] ?><br>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="error-message">Are you sure you have visited the hospital before?</p><br>
      <a href="not_visited.php" class="go-home-btn">Try as new patient</a>
    <?php endif; ?>
  <?php endif; ?>

</body>
</html>
