<?php
include 'conn.php';

session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header('Location: login.php');
    exit();
}

// Fetch disease advisory information from the database
$diseaseAdvisoryQuery = "SELECT * FROM tbldiseaseadvisory";
$diseaseAdvisoryResult = $conn->query($diseaseAdvisoryQuery);

// Initialize $diseaseAdvisoryArray as an empty array
$diseaseAdvisoryArray = array();

// Check if there are rows in the result
if ($diseaseAdvisoryResult && $diseaseAdvisoryResult->num_rows > 0) {
    $diseaseAdvisoryArray = $diseaseAdvisoryResult->fetch_all(MYSQLI_ASSOC);
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
            <h1>DISEASE INFORMATION</h1><br>
            <center>
                <table>
                    <tr>
                        <th>Disease</th>
                        <th>Advisory</th>
                        <th>Treatment/Intervention plan</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($diseaseAdvisoryArray as $diseaseAdvisory): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($diseaseAdvisory['disease']); ?></td>
                            <td>
                                <?php
                                if (empty($diseaseAdvisory['advisory'])) {
                                    echo "No advisory available";
                                } else {
                                    echo htmlspecialchars($diseaseAdvisory['advisory']);
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (empty($diseaseAdvisory['intervention'])) {
                                    echo "No intervention plan available";
                                } else {
                                    echo htmlspecialchars($diseaseAdvisory['intervention']);
                                }
                                ?>
                            </td>
                            <td>
                              <a href="editdisease.php?id=<?php echo $diseaseAdvisory['id']; ?>"><button class="edit"><i class="fas fa-edit"></i></button></a>
                              <!-- <button class="view" type="button" title="View Record"><i class="fas fa-eye"></i></button></a> -->
                            </td>


                        </tr>
                    <?php endforeach; ?>
                </table>
            </center>
        </div>
    </div>
    <script src="effect.js"></script>

</body>
</html>
