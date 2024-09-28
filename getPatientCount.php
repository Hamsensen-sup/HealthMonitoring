<?php
include 'conn.php';

$result = array();

if (isset($_POST['year'])) {
    $selectedYear = $_POST['year'];

    if (isset($_POST['disease']) && $_POST['disease'] !== 'all') {
        $selectedDisease = $_POST['disease'];

        for ($i = 1; $i <= 7; $i++) {
            $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM tblrecord WHERE street LIKE 'Purok $i%' AND year = '$selectedYear' AND disease = '$selectedDisease'");
            $row = mysqli_fetch_assoc($query);
            $result[] = $row['count'];
        }
    } else {
        for ($i = 1; $i <= 7; $i++) {
            $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM tblrecord WHERE street LIKE 'Purok $i%' AND year = '$selectedYear'");
            $row = mysqli_fetch_assoc($query);
            $result[] = $row['count'];
        }
    }

    echo json_encode($result);
    exit();
}

// Handle the case when only disease is selected
if (isset($_POST['disease']) && $_POST['disease'] !== 'all') {
    $selectedDisease = $_POST['disease'];

    for ($i = 1; $i <= 7; $i++) {
        $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM tblrecord WHERE street LIKE 'Purok $i%' AND disease = '$selectedDisease'");
        $row = mysqli_fetch_assoc($query);
        $result[] = $row['count'];
    }

    echo json_encode($result);
    exit();
}
?>
