<?php
include 'conn.php';

session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // Now you can use $username to display the logged-in user's name or perform other actions.
} else {
    // If the session variable is not set, the user is not logged in; you can redirect them to the login page or take appropriate action.
    header('Location: login.php');
    exit();
}

// Handle the form submission for data deletion
if (isset($_POST['selectedRecordIds'])) {
    $selectedRecordIds = $_POST['selectedRecordIds'];
    $recordId = $_GET['recordid'];

    // Perform the deletion of selected records based on the $selectedRecordIds array
    $selectedRecordIdsArray = explode(",", $selectedRecordIds);
    foreach ($selectedRecordIdsArray as $selectedId) {
        // Add validation and security checks here before executing the DELETE query.
        $sql = "DELETE FROM tbldiseaseinfo WHERE id = '$selectedId' AND recordid = '$recordId'";
        if (mysqli_query($conn, $sql)) {
            // Data deleted successfully
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    // Redirect back to your page with the recordId after deletion
    header("Location: " . $_SERVER['PHP_SELF'] . "?recordid=$recordId");
    exit();
}
// Check if the form is submitted
if(isset($_POST['addrecordbtn'])) {
    $recordId = $_POST['recordid'];
    $date_of_visit = $_POST['date_of_visit'];
    $bp = $_POST['bp'];
    $hrv = $_POST['hrv'];
    $bt = $_POST['bt'];
    $bg = $_POST['bg'];
    $purpose = $_POST['purpose'];
    $medication = $_POST['medication'];
    $advise = $_POST['advise'];

    // Assuming your tbldiseaseinfo has a column named 'recordid' to associate data with a specific record
    $sql = "INSERT INTO tbldiseaseinfo(recordid, date_of_visit, bp, hrv, bt, bg, purpose, medication, advise)
            VALUES ('$recordId', '$date_of_visit', '$bp', '$hrv', '$bt', '$bg', '$purpose', '$medication', '$advise')";

    if(mysqli_query($conn, $sql)) {
      header("Location: " . $_SERVER['PHP_SELF'] . "?recordid=$recordId");
 // Redirect back to your page with the recordId
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if (isset($_GET['recordid'])) {
    $recordId = $_GET['recordid'];

    $sql = "SELECT * FROM tblrecord WHERE id = '$recordId'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);
        // Now you can use $record['fieldname'] to display the record data
    } else {
        echo "Record not found!";
        exit();
    }
    $diseaseInfoQuery = "SELECT * FROM tbldiseaseinfo WHERE recordid = '$recordId'";
    $diseaseInfoResult = mysqli_query($conn, $diseaseInfoQuery);

} else {
    echo "Invalid request!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
 <?php include 'admin-header.php'?>
 <?php include 'admin-sidebar.php'?>
    <div class="spacing">
      <div class = "profile-margin">
        <div class = "left-container">
          <button type="button" id="returnButton" >Return</button>
          <br><br>
          <!-- Add this line to display the image with fallback -->
          <center>
              <?php
              $imagePath = $record['image'];
              $fallbackImage = 'silhouette.jpeg'; // Replace with the actual path to your silhouette image

              if (!empty($imagePath) && file_exists($imagePath)) {
                  echo "<img src=\"$imagePath\" alt=\"Patient Image\" class=\"profile-img\">";
              } else {
                  echo "<img src=\"$fallbackImage\" alt=\"Silhouette Image\" class=\"profile-img\">";
              }
              ?>
          </center>

          <center><h2><?= $record['lastname'] ?>, <?= $record['firstname'] ?> <?= $record['middlename'] ?></h2></center><br>
          <hr><br>
          <!-- <a href="edit.php?editid=<?= $recordId ?>"><button type="button">Edit Information</button></a> -->
          <p><b>Age: </b><?= $record['age'] ?></p>
          <p><b>Sex: </b><?= $record['sex'] ?></p>
          <p><b>Barangay: </b><?= $record['barangay'] ?></p>
          <p><b>Purok: </b><?= $record['street'] ?></p>
          <p><b>House no. : </b><?= $record['houseno'] ?></p>
          <p><b>Contact Number: </b><?= $record['contact'] ?></p>
          <p><b>Height(cm): </b><?= $record['height'] ?> cm</p>
          <p><b>Weight(kg): </b><?= $record['weight'] ?> kg</p>
        </div>

        <div class = "right-container">
          <h2>CURRENT DISEASE INFORMATION</h2>
          <button id="addDataRowBtn">Add Data</button>
          <button type="button" id="deleteDataBtn">Delete Data</button>

          <p><b>Disease name: </b><?= $record['disease'] ?></p>
          <p><b>Date of Ocurence: </b><?= $record['month'] ?> <?= $record['year'] ?></p>
          <form method="POST" id="deleteDataForm">
          <table id="healthTable">
            <tr>
              <th>Date of visit</th>
              <th>Purpose</th>
              <th>Medication</th>
              <th>Advise</th>
              <th>Action</th>
            </tr>
            <?php
            $diseaseInfoRowCount = mysqli_num_rows($diseaseInfoResult);
            if($diseaseInfoRowCount > 0) {
                mysqli_data_seek($diseaseInfoResult, 0);  // Make sure to reset the pointer
                while($diseaseInfo = mysqli_fetch_assoc($diseaseInfoResult)) {
                    echo "<tr>";
                    echo "<td style=\"text-align: center;\">" . $diseaseInfo['date_of_visit'] . "</td>";
                    echo "<td>" . $diseaseInfo['purpose'] . "</td>";
                    echo "<td>" . $diseaseInfo['medication'] . "</td>";
                    echo "<td>" . $diseaseInfo['advise'] . "</td>";
                    echo "<td style=\"text-align: center;\"><input type='checkbox' name='selected_records[]' value='" . $diseaseInfo['id'] . "'></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td style=\"text-align: center;\" colspan='5'>No disease information data available.</td></tr>";
            }
            ?>
          </table>
        </form>

          <h2>VITAL SIGNS</h2>
          <table>
            <tr>
              <th>Date measured</th>
              <th>BP</th> <!--Blood Pressure-->
              <th>HRV</th> <!--Heart Rate-->
              <th>BT</th> <!--Body Temperature-->
              <th>BG</th> <!--Blood GLucose-->
            </tr>
            <?php
            mysqli_data_seek($diseaseInfoResult, 0);
            $diseaseInfoRowCount = mysqli_num_rows($diseaseInfoResult);
            if($diseaseInfoRowCount > 0) {
                mysqli_data_seek($diseaseInfoResult, 0);  // Make sure to reset the pointer
                while($diseaseInfo = mysqli_fetch_assoc($diseaseInfoResult)) {
                    echo "<tr>";
                    echo "<td style=\"text-align: center;\">" . $diseaseInfo['date_of_visit'] . "</td>";
                    echo "<td>" . $diseaseInfo['bp'] . "mm Hg</td>";
                    echo "<td>" . $diseaseInfo['hrv'] . "ms</td>";
                    echo "<td>" . $diseaseInfo['bt'] . "ºC</td>";
                    echo "<td>" . $diseaseInfo['bg'] . "mg/dL</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td style=\"text-align: center;\" colspan='5'>No vital signs data available.</td></tr>";
            }

            ?>
          </table>
        </div>
      </div>
    </div>
    <script src="effect.js"></script>
    <script>
    // Add to effect.js
    document.addEventListener("DOMContentLoaded", function() {
        var modal = document.getElementById("addDataRowModal");
        var btn = document.getElementById("addDataRowBtn");
        var span = document.getElementsByClassName("close-btn")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    });
    document.getElementById("returnButton").addEventListener("click", function() {
        window.location.href = 'records.php';
    });

    document.getElementById("deleteDataBtn").addEventListener("click", function () {
      var selectedRecords = document.querySelectorAll('input[type="checkbox"]:checked');
      var selectedRecordIds = [];

      selectedRecords.forEach(function (checkbox) {
        selectedRecordIds.push(checkbox.value);
      });

      if (selectedRecordIds.length > 0) {
        if (confirm("Are you sure you want to delete the selected data?")) {
          // Create a hidden input field to send the selected record IDs to the server.
          var hiddenInput = document.createElement("input");
          hiddenInput.type = "hidden";
          hiddenInput.name = "selectedRecordIds";
          hiddenInput.value = selectedRecordIds.join(",");
          document.getElementById("deleteDataForm").appendChild(hiddenInput);

          // Submit the form to trigger the PHP code for data deletion.
          document.getElementById("deleteDataForm").submit();
        }
      } else {
        alert("No records selected for deletion.");
      }
    });
    </script>


    <div id="addDataRowModal" class="health-modal">
        <div class="healthmodal-content">
            <span class="close-btn">&times;</span>
            <center><h1>ADD NEW DATA</h1></center>
            <p><b>*IMPORTANT NOTE: </b>Ensure the accuracy of the information entered in this form, as health data modifications are not permitted to maintain data integrity and reliability.</p>
            <form method="POST">
                <div class="modal-columns"> <!-- Using Flexbox for side-by-side columns -->
                    <!-- Left Column -->
                    <div class="modal-left">

                        <label for="date_of_visit">Date of visit:</label>
                        <input type="date" id="date_of_visit" name="date_of_visit" class="enlarged-input" required>

                        <label for="bp">BP (Blood Pressure):</label>
                        <input type="text" id="bp" name="bp">

                        <label for="hrv">HRV (Heart Rate):</label>
                        <input type="text" id="hrv" name="hrv">

                        <label for="bt">BT (Body Temperature):</label>
                        <input type="text" id="bt" name="bt">

                        <label for="bg">BG (Blood Glucose):</label>
                        <input type="text" id="bg" name="bg">

                    </div>

                    <!-- Right Column -->
                    <div class="modal-right">
                      <input type="hidden" name="recordid" value="<?= $recordId ?>">
                      <label for="purpose">Purpose:</label>
                      <textarea name="purpose" rows="3" cols="13" placeholder="Add the description here..."></textarea><br>

                      <label for="medication">Medication:</label>
                      <textarea name="medication" rows="5" cols="13" placeholder="Add the description here..."></textarea><br>

                      <label for="advise">Advise:</label>
                      <textarea name="advise" rows="5" cols="13" placeholder="Add the description here..."></textarea><br>

                    </div>
                </div>

                <div class="modal-footer"> <!-- For the button to span the full width under the columns -->
                    <button type="text" class="addrowbtn" name="addrecordbtn">Add</button>

                </div>
            </form>
        </div>
    </div>
  </body>
</html>
