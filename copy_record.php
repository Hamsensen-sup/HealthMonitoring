<?php
include 'conn.php';

session_start(); // Start the session

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // Now you can use $username to display the logged-in user's name or perform other actions.
} else {
    // If the session variable is not set, the user is not logged in; you can redirect them to the login page or take appropriate action.
    header('Location: login.php');
    exit();
}

if (isset($_GET['recordid'])) {
    $recordId = $_GET['recordid'];

    // Retrieve the record data to be copied
    $sql = "SELECT * FROM tblrecord WHERE id = '$recordId'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Insert a new record with the same data
        $insertSql = "INSERT INTO tblrecord (lastname, firstname, middlename, contact, houseno, street, age, height, weight, sex)
                      VALUES ('" . $row['lastname'] . "', '" . $row['firstname'] . "', '" . $row['middlename'] . "', '" . $row['contact'] . "', '" . $row['houseno'] . "', '" . $row['street'] . "', '" . $row['age'] . "', '" . $row['height'] . "', '" . $row['weight'] . "', '" . $row['sex'] . "')";
        $insertResult = mysqli_query($conn, $insertSql);

        if ($insertResult) {
            echo "Record copied successfully.";
        } else {
            echo "Error copying the record: " . mysqli_error($conn);
        }
    } else {
        echo "Record not found.";
    }
} else {
    echo "Record ID not provided.";
}

mysqli_close($conn);
?>
