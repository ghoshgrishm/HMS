<?php
include("../database.php");

if (isset($_GET['term'])) {
    $term = mysqli_real_escape_string($conn, $_GET['term']);
    $sql = "SELECT DISTINCT medicine_name FROM stock WHERE medicine_name LIKE '%$term%' LIMIT 10";
    $result = mysqli_query($conn, $sql);
    
    $suggestions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $suggestions[] = $row['medicine_name'];
    }

    echo json_encode($suggestions);
    mysqli_close($conn);
}
?>
