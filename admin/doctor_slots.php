<?php
include("../database.php");
date_default_timezone_set("Asia/Kolkata");

$time_slots = [
    'eight_to_nine', 'nine_to_ten', 'ten_to_eleven', 'eleven_to_twelve',
    'twelve_to_one', 'one_to_two', 'two_to_three', 'three_to_four',
    'four_to_five', 'five_to_six', 'six_to_seven', 'seven_to_eight',
    'eight_to_nine_pm', 'nine_to_ten_pm'
];

// Get all doctor IDs
$doctors = [];
$result = mysqli_query($conn, "SELECT doctor_id FROM doctor");
while ($row = mysqli_fetch_assoc($result)) {
    $doctors[] = $row['doctor_id'];
}

// Get next week's Monday to Sunday
$start_date = date("Y-m-d", strtotime("monday next week"));
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $dates[] = date("Y-m-d", strtotime("+$i day", strtotime($start_date)));
}

// Insert slots for each doctor for each day
foreach ($doctors as $doctor_id) {
    foreach ($dates as $date) {
        // Prevent duplicates
        $check = mysqli_query($conn, "SELECT * FROM slot WHERE doctor_id = $doctor_id AND date = '$date'");
        if (mysqli_num_rows($check) > 0) continue;

        $columns = "doctor_id, date, " . implode(", ", $time_slots);
        $values = [$doctor_id, "'$date'"];
        foreach ($time_slots as $slot) {
            $values[] = "'unavailable'";
        }
        $valueString = implode(", ", $values);

        $sql = "INSERT INTO slot ($columns) VALUES ($valueString)";
        mysqli_query($conn, $sql);
    }
}

mysqli_close($conn);
