<?php
include 'conn.php';

session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Disease Information</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Adjust the width of the Advisory and Intervention Plan columns */
    td:nth-child(2), td:nth-child(3) {
        width: 40%;
    }
</style>
</head>
<body>
    <?php include 'admin-header.php'; ?>
    <?php include 'admin-sidebar.php'; ?>
    <div class="spacing">
        <div class="record-content">
            <h1>INSERT DISEASE NAME</h1><br>
            <center>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Medicine Name</th>
                        <th>Quantity on Hand</th>
                        <th>Quantity Administered</th>
                        <th>Quantity Remaining</th>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                </table>
            </center>
        </div>
    </div>
    <script src="effect.js"></script>

</body>
</html>
