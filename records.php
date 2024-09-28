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

if (isset($_GET['deleteid'])) {
  $recordId = $_GET['deleteid'];

  // Delete the record from the database
  $sql = "DELETE FROM tblrecord WHERE id = '$recordId'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    // Record deleted successfully
    mysqli_close($conn);
    echo "<script>alert('Record has been deleted successfully!'); window.location.href = 'records.php';</script>";
    exit();
  } else {
    // Error deleting the record
    mysqli_close($conn);
    echo "<script>alert('Error deleting the record: " . mysqli_error($conn) . "'); window.location.href = 'records.php';</script>";
    exit();
  }
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Record Management</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <?php include 'admin-header.php'; ?>
    <?php include 'admin-sidebar.php'; ?>
    <div class="spacing">
      <div class="record-content">
        <?php
        // Include the database connection file (conn.php)
        include 'conn.php';

        // Fetch the total number of users from the tblrecord table
        $sql = "SELECT COUNT(*) AS totalUsers FROM tblrecord";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $totalUsers = $row['totalUsers'];

        // Display the total number of users
        echo "<p>Total Patients: " . $totalUsers . "</p>";

        // Close the database connection
        mysqli_close($conn);
        ?>
      </div>
      <div class="record-content">
        <h1>HEALTH RECORD MANAGEMENT</h1>
        <div class="search-bar">
          <input type="text" id="search" name="search" placeholder="Search by surname or first name..." onkeydown="handleSearch(event)">
          <button type="button" name="searchbtn" class="searchbtn" onclick="searchRecords()">Search</button>
          <button type="button" class="sortbtn" onclick="sortRecords()"><i class="fas fa-sort"></i>Sort by surname</button>
          <button type="button" class="sortbtn" onclick="location.reload();">Refresh</button>
        </div>
        <p><b>NOTE: </b>Here is the health record management page if you want to manage health records and view form of the patients.</p>
        <center>
        <table id="record-table" style="text-transform: capitalize;">
          <tr>
            <!-- <th>ID</th> -->
            <!-- <th>Duplicate</th> -->
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Contact</th>
            <!-- <th>House no.</th> -->
            <th>Purok</th>
            <!-- <th>Barangay</th> -->
            <th>Disease</th>
            <th>Time Added</th>
            <th>Action</th>
          </tr>

          <?php
          include 'conn.php';

          $sql = "SELECT * FROM tblrecord ORDER BY id DESC";
          $result = mysqli_query($conn, $sql);

          // Check if any records exist
          if (mysqli_num_rows($result) > 0) {
            // Loop through each record and display it in the table
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              // echo "<td>{$row['id']}</td>";
              // echo '<td><button class="copy" type="button" title="Duplicate Record" onclick="copyRecord(' . $row['id'] . ')"><i class="fas fa-clone"></i></button></td>';
              echo "<td>{$row['lastname']}</td>";
              echo "<td>{$row['firstname']}</td>";
              echo "<td>{$row['middlename']}</td>";
              echo "<td>{$row['contact']}</td>";
              // echo "<td>{$row['houseno']}</td>";
              echo "<td>{$row['street']}</td>";
              // echo "<td>{$row['barangay']}</td>";
              echo "<td>{$row['disease']}</td>";
              echo "<td>{$row['timestamp']}</td>";
              echo '<td>
                      <a href="viewform.php?recordid=' . htmlentities($row['id']) . '"><button class="view" type="button" title="View Record"><i class="fas fa-eye"></i></button></a>
                      <button class="delete" type="button" title="Delete Record" onclick="confirmDelete(' . $row['id'] . ')"><i class="fas fa-trash"></i></button>
                    </td>';
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='9'>No records found.</td></tr>";
          }

          mysqli_close($conn);
          ?>

          <script>
              function copyRecord(recordId) {
                  var confirmation = confirm("Are you sure you want to copy this record?");

                  if (confirmation) {
                      // Send an AJAX request to copy the record
                      var xhttp = new XMLHttpRequest();
                      xhttp.onreadystatechange = function() {
                          if (this.readyState === 4 && this.status === 200) {
                              // Refresh the table after successfully copying the record
                              location.reload();
                          }
                      };
                      xhttp.open("GET", "copy_record.php?recordid=" + recordId, true);
                      xhttp.send();
                  }
              }
          </script>

        </table>
      </center>
        <p id="search-result" style="display: none;">Record does not exist.</p>
      </div>
    </div>
    <script>
      function searchRecords() {
        // Retrieve the search query
        var searchQuery = document.getElementById("search").value.toLowerCase();

        // Get table elements
        var table = document.getElementById("record-table");
        var header = table.getElementsByTagName("tr")[0];
        var rows = table.getElementsByTagName("tr");

        // Keep track of whether any matching record was found
        var foundMatch = false;

        // Loop through each row and check if it matches the search query
        for (var i = 1; i < rows.length; i++) {
          var surname = rows[i].getElementsByTagName("td")[0].innerText.toLowerCase();
          var firstName = rows[i].getElementsByTagName("td")[1].innerText.toLowerCase();
          var fullName = surname + " " + firstName; // Concatenate surname and first name
          if (fullName.includes(searchQuery)) {
            rows[i].style.display = "";
            foundMatch = true;
          } else {
            rows[i].style.display = "none";
          }
        }

        // Display message if no matching record was found and hide table header
        var searchResultElement = document.getElementById("search-result");
        if (foundMatch) {
          searchResultElement.style.display = "none";
          header.style.display = "";
        } else {
          searchResultElement.style.display = "";
          header.style.display = "none";
        }
      }

      function handleSearch(event) {
        if (event.keyCode === 13) {
          event.preventDefault();
          searchRecords();
        }
      }

      function confirmDelete(recordId) {
        var confirmation = confirm("Are you sure you want to delete this record?");

        if (confirmation) {
          window.location.href = "records.php?deleteid=" + recordId;
        }
      }

      var isAscending = true; // Initialize as ascending order

      function sortRecords() {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("record-table");
        switching = true;

        while (switching) {
          switching = false;
          rows = table.rows;

          for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("td")[1];
            y = rows[i + 1].getElementsByTagName("td")[1];

            if (isAscending) {
              // Sort in ascending order
              if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
              }
            } else {
              // Sort in descending order
              if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
              }
            }
          }

          if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
          }
        }

        // Toggle the sorting order for the next click
        isAscending = !isAscending;
      }


    </script>
    <script src="effect.js"></script>
  </body>
</html>
