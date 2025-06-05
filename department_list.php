<?php
    include("database.php");

    $sql = "SELECT * FROM department";
    $result = mysqli_query($conn, $sql);

    echo "<h2 style='text-align:center;'>Hospital Departments</h2>";
    echo "<div style='display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;'>";

    if (mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            echo "<div style='border: 2px solid #007BFF; border-radius: 10px; padding: 20px; width: 250px; background-color: #f2f8ff; box-shadow: 2px 2px 10px rgba(0,0,0,0.1);'>";
            echo "<h3><a style='text-decoration: none; color: #007BFF;' href='doctor_list.php?dept_id=" . $row["department_id"] . "'>" . $row["dept_name"] . "</a></h3>";
            echo "<p>" . $row["description"] . "</p>";
            echo "<p><strong>Head:</strong> " . $row["dept_head"] . "</p>";
            echo "</div>";
        }
    }
    else {
        echo "No department found";
    }

    echo "</div>";
    mysqli_close($conn);
?>
