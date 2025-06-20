<?php
include("database.php");

// Get all doctors
$doctor_ids = [];
$res = mysqli_query($conn, "SELECT doctor_id FROM doctor");
while ($row = mysqli_fetch_assoc($res)) {
    $doctor_ids[] = $row['doctor_id'];
}

// Start from next Monday
$nextMonday = strtotime("next Monday");
$time_slots = [
    'eight_to_nine', 'nine_to_ten', 'ten_to_eleven', 'eleven_to_twelve',
    'twelve_to_one', 'one_to_two', 'two_to_three', 'three_to_four',
    'four_to_five', 'five_to_six', 'six_to_seven', 'seven_to_eight',
    'eight_to_nine_pm', 'nine_to_ten_pm'
];

for ($i = 0; $i < 7; $i++) {
    $date = date("Y-m-d", strtotime("+$i days", $nextMonday));

    foreach ($doctor_ids as $doctor_id) {
        $check = $conn->prepare("SELECT 1 FROM slot WHERE doctor_id = ? AND date = ?");
        $check->bind_param("is", $doctor_id, $date);
        $check->execute();
        $check->store_result();

        if ($check->num_rows == 0) {
            $cols = implode(", ", $time_slots);
            $placeholders = rtrim(str_repeat("'unavailable', ", count($time_slots)), ", ");
            $sql = "INSERT INTO slot (doctor_id, date, $cols) VALUES (?, ?, $placeholders)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $doctor_id, $date);
            $stmt->execute();
        }

        $check->close();
    }
}

echo "Next week's slots generated.";
mysqli_close($conn);
?>
