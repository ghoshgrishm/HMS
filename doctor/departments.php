<?php
include("../database.php");

$sql = "SELECT * FROM department";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Departments</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f9ff;
            color: #003366;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #003366;
            font-size: 32px;
            margin-bottom: 30px;
            border-bottom: 3px solid #007BFF;
            display: inline-block;
            padding-bottom: 5px;
        }

        .departments-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            padding: 20px;
        }

        .department-card {
            background-color: #ffffff;
            border: 2px solid #007BFF;
            border-radius: 12px;
            padding: 25px;
            width: 280px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .department-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .department-card h3 a {
            text-decoration: none;
            color: #007BFF;
            font-size: 22px;
        }

        .department-card p {
            margin: 10px 0;
            color: #333;
            font-size: 15px;
            line-height: 1.5;
        }

        .go-home-btn {
            margin: 40px auto;
            display: block;
            width: fit-content;
            padding: 10px 20px;
            background: linear-gradient(to right, #4a00e0, #8e2de2);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }

        .go-home-btn:hover {
            background: linear-gradient(to right, #3a00c0, #7320c0);
        }
    </style>
</head>
<body>

    <h2>Hospital Departments</h2>
    <div class="departments-container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='department-card'>";
                echo "<h3><a href='doctor_list.php?dept_id=" . $row["department_id"] . "'>" . htmlspecialchars($row["dept_name"]) . "</a></h3>";
                echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                echo "<p><strong>Head:</strong> " . htmlspecialchars($row["dept_head"]) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center;'>No departments found.</p>";
        }
        ?>
    </div>
    <br>
    <a href="home_doctor.php" class="go-home-btn">Go Home</a>

</body>
</html>

<?php
mysqli_close($conn);
?>
