<?php
include 'conn.php';

// Check if disease and year are set
if (isset($_POST['disease'], $_POST['year'])) {
    $disease = $conn->real_escape_string($_POST['disease']);
    $year = $conn->real_escape_string($_POST['year']);
    $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    $results = [];

    // Fetch monthly counts
    foreach ($months as $month) {
        if ($disease == 'all') {
            // If 'all' diseases are selected, then don't filter by disease
            $query = "SELECT COUNT(*) as count FROM tblrecord WHERE month='$month' AND year='$year'";
        } else {
            $query = "SELECT COUNT(*) as count FROM tblrecord WHERE disease='$disease' AND month='$month' AND year='$year'";
        }

        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $results[] = $row['count'];
    }

    echo json_encode($results);
} else {
    echo json_encode(['error' => 'Invalid parameters provided.']);
}

?>
