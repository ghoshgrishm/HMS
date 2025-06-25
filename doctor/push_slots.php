<?php
include("../database.php");
session_start();
$status = "";
$slots_pushed = false;

if (!isset($_SESSION['username'])) {
    die("You must be logged in to access this page.");
}

$username = $_SESSION['username'];
$doctor_id = null;

$sql = "SELECT doctor_id FROM user WHERE username = '$username' AND user_type = 'doctor'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $doctor_id = $row['doctor_id'];
} else {
    die("Doctor ID not found. Please ensure you're registered as a doctor.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slot_data'])) {
    $data = json_decode($_POST['slot_data'], true);

    foreach ($data as $entry) {
        $date = $entry['date'];
        foreach ($entry['slots'] as $slot) {
            $start_time = $slot;
            $end_time = date("H:i:s", strtotime($slot) + 30 * 60);
            $sql = "INSERT INTO slot (doctor_id, date, start_time, end_time, status)
                    VALUES ('$doctor_id', '$date', '$start_time', '$end_time', 'available')";
            mysqli_query($conn, $sql);
        }
    }
    $status = "Slots successfully added!";
    $slots_pushed = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Push Slots</title>
    <link rel="stylesheet" href="hospital.css">
    <style>
        /* All your existing CSS remains unchanged */
        * { box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            background: white;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            font-size: 2.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            color: #003366;
            text-shadow: 0 2px 4px rgba(0, 51, 102, 0.1);
            position: relative;
        }
        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #003366, #0066cc);
            border-radius: 2px;
        }
        .main-container { max-width: 900px; width: 100%; text-align: center; }
        h3 { font-size: 1.5rem; color: #27ae60; margin-top: 30px; }
        h4 { font-size: 1.2rem; color: #003366; margin: 20px 0 15px 0; background: #f8f9fa; padding: 10px; border-radius: 8px; }
        .box {
            display: inline-block;
            padding: 12px 20px;
            margin: 8px;
            border: 2px solid #3498db;
            border-radius: 12px;
            cursor: pointer;
            background-color: white;
            color: #3498db;
            transition: all 0.3s ease;
            font-weight: 500;
            min-width: 80px;
            text-align: center;
        }
        .box:hover { border-color: #2980b9; }
        .box.selected {
            background-color: #27ae60;
            color: white;
            border-color: #27ae60;
        }
        .slots-container { margin-top: 30px; display: none; }
        .slots-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e9ecef;
        }
        #dateBoxes {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .status-message {
            color: #27ae60;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
        }
        input[type="submit"] {
            background: linear-gradient(135deg, #003366, #0066cc);
            box-shadow: 0 4px 15px rgba(0, 51, 102, 0.3);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px;
        }
        input[type="submit"]:hover {
            background: linear-gradient(135deg, #002244, #0052a3);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 51, 102, 0.4);
        }
        #slotSelectors {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        @media (max-width: 768px) {
            .main-container { padding: 20px; margin: 10px; }
            h1 { font-size: 2rem; padding: 15px 25px; }
            .box { padding: 10px 15px; margin: 5px; min-width: 70px; }
        }
    </style>
</head>
<body>
<h1>Push Your Available Slots</h1>

<div class="main-container">
    <?php if ($status) echo "<div class='status-message'>$status</div>"; ?>

    <?php if (!$slots_pushed): ?>
    <form method="post" id="slotForm">
        <div>
            <h3>Select Available Dates (Next Week)</h3>
            <div id="dateBoxes"></div>
        </div>

        <div id="slotsContainer" class="slots-container">
            <h3>Select Time Slots for Each Date</h3>
            <div id="slotSelectors"></div>
        </div>

        <input type="hidden" name="slot_data" id="slotDataInput">
        <br><br>
        <input type="submit" name="submit-btn" value="Submit Slots">
    </form>
    <?php endif; ?>
</div>

<script>
const dateBoxesContainer = document.getElementById('dateBoxes');
const slotSelectorsContainer = document.getElementById('slotSelectors');
const slotsContainer = document.getElementById('slotsContainer');

const today = new Date();
const nextMonday = new Date(today.setDate(today.getDate() + (8 - today.getDay()) % 7));
const dateOptions = { weekday: 'short', month: 'short', day: 'numeric' };
const dates = [];

for (let i = 0; i < 7; i++) {
    let d = new Date(nextMonday);
    d.setDate(d.getDate() + i);
    let iso = d.toISOString().split('T')[0];
    dates.push({ label: d.toLocaleDateString('en-US', dateOptions), value: iso });
}

// Step 1: Display date boxes
dates.forEach(date => {
    const box = document.createElement('div');
    box.classList.add('box');
    box.textContent = date.label;
    box.dataset.value = date.value;
    box.onclick = () => {
        box.classList.toggle('selected');
        updateSlotInputs();
    };
    dateBoxesContainer.appendChild(box);
});

let selectedSlotsMap = {};

function updateSlotInputs() {
    const selectedDates = Array.from(document.querySelectorAll('#dateBoxes .box.selected'))
        .map(b => ({ label: b.textContent, value: b.dataset.value }));

    slotSelectorsContainer.innerHTML = '';
    slotsContainer.style.display = selectedDates.length ? 'block' : 'none';

    selectedDates.forEach(date => {
        const box = document.createElement('div');
        box.classList.add('slots-box');

        const title = document.createElement('h4');
        title.textContent = `${date.label} (${date.value})`;
        box.appendChild(title);

        const slotTimes = generateTimeSlots("09:00", "17:00", 30);
        const selectedForDate = selectedSlotsMap[date.value] || [];

        slotTimes.forEach(time => {
            const timeBox = document.createElement('div');
            timeBox.classList.add('box');
            timeBox.textContent = time;
            timeBox.dataset.time = time;

            if (selectedForDate.includes(time)) {
                timeBox.classList.add('selected');
            }

            timeBox.onclick = () => {
                timeBox.classList.toggle('selected');
                const time = timeBox.dataset.time;
                if (!selectedSlotsMap[date.value]) selectedSlotsMap[date.value] = [];

                if (timeBox.classList.contains('selected')) {
                    if (!selectedSlotsMap[date.value].includes(time)) {
                        selectedSlotsMap[date.value].push(time);
                    }
                } else {
                    selectedSlotsMap[date.value] = selectedSlotsMap[date.value].filter(t => t !== time);
                }
            };

            box.appendChild(timeBox);
        });

        box.dataset.date = date.value;
        slotSelectorsContainer.appendChild(box);
    });
}

function generateTimeSlots(start, end, step) {
    const slots = [];
    let [h, m] = start.split(':').map(Number);
    const [endH, endM] = end.split(':').map(Number);
    while (h < endH || (h === endH && m < endM)) {
        slots.push(`${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`);
        m += step;
        if (m >= 60) {
            h++;
            m -= 60;
        }
    }
    return slots;
}

document.getElementById('slotForm')?.addEventListener('submit', function () {
    const result = [];

    for (const date in selectedSlotsMap) {
        if (selectedSlotsMap[date].length > 0) {
            result.push({ date, slots: selectedSlotsMap[date] });
        }
    }

    if (result.length === 0) {
        alert("Please select at least one slot.");
        return false;
    }

    document.getElementById('slotDataInput').value = JSON.stringify(result);
    return true;
});
</script>
<br>
<a href="home_doctor.php" class="go-home-btn">Go home</a>
</body>
</html>
