<?php
include 'conn.php';

session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $userId = mysqli_real_escape_string($conn, $_GET['id']);

    // Check if there is more than one admin
    $countQuery = "SELECT COUNT(*) as adminCount FROM adminusers";
    $countResult = mysqli_query($conn, $countQuery);
    $countRow = mysqli_fetch_assoc($countResult);
    $adminCount = $countRow['adminCount'];

    if ($adminCount > 1) {
        // Delete the admin user from the adminusers table
        $deleteQuery = "DELETE FROM adminusers WHERE id = '$userId'";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        // Check if deletion was successful and respond to the AJAX request
        if ($deleteResult) {
            echo 'success';
            exit();
        } else {
            echo 'failure';
            exit();
        }
    } else {
        echo 'last_admin_failure';
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addnewAdminBtn'])) {
    // Process the form submission
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $newLastName = mysqli_real_escape_string($conn, $_POST['lastname']);
    $newFirstName = mysqli_real_escape_string($conn, $_POST['firstname']);
    $newMiddleName = mysqli_real_escape_string($conn, $_POST['middlename']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $newContact = mysqli_real_escape_string($conn, $_POST['contact']);

    // Insert data into the adminusers table
    $insertQuery = "INSERT INTO adminusers (username, password, lastname, firstname, middlename, email, contact) VALUES ('$newUsername', '$newPassword', '$newLastName', '$newFirstName', '$newMiddleName', '$newEmail', '$newContact')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        // Redirect or display a success message as needed
        header('Location: admin-manage.php');
        exit();
    } else {
        // Handle the error, display a message, or redirect as needed
        die("Insert failed: " . mysqli_error($conn));
    }
}

$query = "SELECT id, username, lastname, firstname, middlename, email, contact FROM adminusers";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
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
        <h1>MANAGE ADMINISTRATORS</h1>
        <center>
        <br><button type="button" name="sortbtn" class="adminbtn" id="addAdminBtn">Add new admin</button><br><br>
        <table id="record-table">
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Full name</th>
            <th>E-mail address</th>
            <th>Contact number</th>
            <th>Action</th>
          </tr>
          <?php
          // Loop through the result set and display data in the table
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>{$row['id']}</td>";
              echo "<td>{$row['username']}</td>";
              echo "<td>{$row['lastname']}, {$row['firstname']} {$row['middlename']}</td>";
              echo "<td>{$row['email']}</td>";
              echo "<td>{$row['contact']}</td>";
              echo '<td>
                      <button class="delete" type="button" title="Delete Admin" onclick="confirmDelete(' . $row['id'] . ')"><i class="fas fa-trash"></i></button>
                    </td>';
              echo "</tr>";
          }
          ?>
        </table>
      </center>
      </div>
    </div>
    <script src="effect.js"></script>
    <div class="health-modal" id="addAdminModal">
      <div class="healthmodal-content">
          <span class="close-btn" id="closeBtn">&times;</span>
          <center><h1>ADD NEW ADMIN</h1></center>
          <form method="POST">
              <div class="modal-columns"> <!-- Using Flexbox for side-by-side columns -->
                  <!-- Left Column -->
                  <div class="modal-left">

                      <label for="username">Username:</label>
                      <input type="text" id="username" name="username" class="enlarged-input" required>

                      <label for="password">Password:</label>
                      <input type="password" id="password" name="password" class="enlarged-input" required><br>

                      <label for="email">E-mail address:</label>
                      <input type="text" id="email" name="email" class="enlarged-input" required>

                      <label for="username">Contact Number:</label>
                      <input type="text" id="contact" name="contact" class="enlarged-input" required>

                  </div>

                  <!-- Right Column -->
                  <div class="modal-right">

                    <label for="firsname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" class="enlarged-input" required>

                    <label for="middlename">Middle Name:</label>
                    <input type="text" id="middlename" name="middlename" class="enlarged-input">

                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" class="enlarged-input" required>

                  </div>
              </div>

              <div class="modal-footer">
                  <button type="submit" class="addrowbtn" name="addnewAdminBtn">Add</button>
              </div>
          </form>
      </div>
    </div>
    <script>

    function confirmDelete(userId) {
        var confirmDelete = confirm("Are you sure you want to delete this admin?");
        if (confirmDelete) {
            // Send an AJAX request to delete-admin.php
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'admin-manage.php?id=' + userId, true);  // Fix the URL here
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Reload the page after successful deletion
                    var response = xhr.responseText.trim();
                    if (response === 'last_admin_failure') {
                        alert("Data can't be deleted. The system must have one admin.");
                    } else if (response === 'failure') {
                        alert("Failed to delete admin.");
                    } else {
                        location.reload();
                    }
                }
            };
            xhr.send();
        }
    }


        document.getElementById('addAdminBtn').addEventListener('click', function() {
            document.getElementById('addAdminModal').style.display = 'block';
        });

        document.getElementById('closeBtn').addEventListener('click', function() {
            document.getElementById('addAdminModal').style.display = 'none';
        });

        // Close the modal when clicking outside the modal content
        window.addEventListener('click', function(event) {
            var modal = document.getElementById('addAdminModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
  </body>
</html>
