<?php

require_once "conn.php";

session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the specific record based on the ID
    $editDiseaseQuery = "SELECT * FROM tbldiseaseadvisory WHERE id = ?";
    $stmt = $conn->prepare($editDiseaseQuery);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $editDisease = $result->fetch_assoc();
            $diseaseName = $editDisease['disease'];
            $advisory = $editDisease['advisory'];
            $intervention = $editDisease['intervention'];
        } else {
            echo "Error: Record not found";
            // You may redirect the user or take other appropriate action
            exit();
        }
    } else {
        echo "Error: Failed to execute query";
        // You may redirect the user or take other appropriate action
        exit();
    }
    $stmt->close();
} else {
    echo "Error: ID parameter not set";
    // You may redirect the user or take other appropriate action
    exit();
}

// Handle form submission
if (isset($_POST['updateRecordBtn'])) {
    $newAdvisory = $_POST['advisory'];
    $newIntervention = $_POST['intervention'];

    // Update the record in the database
    $updateQuery = "UPDATE tbldiseaseadvisory SET advisory = ?, intervention = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $newAdvisory, $newIntervention, $id);

    if ($stmt->execute()) {
        header('Location: admin-diseaseinfo.php');
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Edit disease</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <?php include 'admin-header.php'; ?>
    <?php include 'admin-sidebar.php'; ?>
    <div class="spacing">
      <div class="record-form">
        <span class="closebtn" id="returnButton">&times;</span>
        <center><h1><?php echo $diseaseName; ?></h1></center>
        <form method="POST" enctype="multipart/form-data">

          <label for="advisory">Advisory</label><br>
          <!-- Use textarea for Advisory -->
          <textarea name="advisory" rows="4" cols="20" placeholder="Add description here..."><?php echo htmlspecialchars($advisory); ?></textarea><br>

          <label for="intervention">Intervention Plan</label><br>
          <!-- Use textarea for Intervention Plan -->
          <textarea name="intervention" rows="7" cols="20" placeholder="Add description here..."><?php echo htmlspecialchars($intervention); ?></textarea><br>

          <div class="modal-footer">
            <center><button type="submit" class="addrowbtn" name="updateRecordBtn">Save Information</button></center>
          </div>
        </form>
      </div>
    </div>
    <script src="effect.js"></script>
    <script>
      document.getElementById('returnButton').addEventListener('click', function() {
        window.location.href = 'admin-diseaseinfo.php';
      });
    </script>
  </body>
</html>
