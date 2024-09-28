<?php
include 'conn.php'; // Include your database connection file

session_start(); // Start the session

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // Now you can use $username to display the logged-in user's name or perform other actions.
} else {
    // If the session variable is not set, the user is not logged in; you can redirect them to the login page or take appropriate action.
    header('Location: login.php');
    exit();
}

if (isset($_POST['upload'])) {

    // File upload
    $target_dir = "uploads/";  // Adjust this to your desired upload directory
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "The file ". basename($_FILES["image"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    // Insert into database
    $headline = $conn->real_escape_string($_POST['headline']);
    $description = $conn->real_escape_string($_POST['description']);
    $sql = "INSERT INTO tblannouncement (image, headline, description) VALUES ('$target_file', '$headline', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Announcement added successfully!'); window.location.href = 'announcement.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch announcements (moved outside the POST check)
$announcements = array();
$sql_fetch = "SELECT * FROM tblannouncement ORDER BY id DESC"; // Fetching in descending order to get latest announcements first
$result = $conn->query($sql_fetch);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $sql = "DELETE FROM tblannouncement WHERE id='$delete_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Announcement deleted successfully!'); window.location.href = 'announcement.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Announcement Board</title>
    <script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.querySelector('.announcement-image');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
      }
    </script>

  </head>
  <body>
    <?php include 'admin-header.php'; ?>
    <?php include 'admin-sidebar.php'; ?>
  <div class="spacing">
    <div class="admin-content">
      <center><h1>ADD ANNOUNCEMENT</h1></center>
    </div>
    <div class="admin-content">
      <p><b>*IMPORTANT NOTE: </b>Ensure the accuracy of the information entered in this form, as announcement tampering are not permitted to maintain data integrity and avoid misinformation.</p>
    </div>

      <form method="POST" enctype="multipart/form-data">
        <div class="announcement-content">
          <div class="add-image">
            <div class="upload-img">
              <img src="default.png" alt="" class="announcement-image"><br>
              <input type="file" name="image" required onchange="previewImage(event)">
            </div>
          </div>
        <div class="announcement">
          <label for="headline">announcement headline: </label><br>
          <textarea name="headline" rows="3" cols="13" placeholder="Add the headline here..." required></textarea>
          <label for="description">announcement description: </label><br>
          <textarea name="description" rows="10" cols="13" placeholder="Add the description here..."required></textarea><br>

          <button class="savebtn" type="submit" name="upload">Save</button>
        </div>
      </form>
    </div>

    <div class="admin-content">
      <center><h1>ANNOUNCEMENT BOARD</h1></center>
    </div>
    <?php foreach ($announcements as $announcement): ?>
    <div class="admin-content">
      <div class="carousel-content">
          <img src="<?php echo htmlspecialchars($announcement['image']); ?>" alt="Announcement Image" class="carousel-image">
          <div class="carousel-text">
              <h1><?php echo htmlspecialchars($announcement['headline']); ?></h1>
              <p><?php echo htmlspecialchars($announcement['description']); ?></p>
              <p><b>Date Announced: </b><?php echo htmlspecialchars($announcement['date']); ?></p>
          </div>
      </div>
      <!-- <button class="edit" type="button"><i class="fas fa-edit"></i>Edit</button> -->
      <button class="deleteAnnouncement" type="button" onclick="confirmDelete(<?php echo $announcement['id']; ?>)"><i class="fas fa-trash"></i>&nbspDelete</button>
    </div>
    <?php endforeach; ?>
  </div>

  <script>
      function confirmDelete(id) {
          var r = confirm("Are you sure you want to delete this announcement?");
          if (r == true) {
              window.location.href = "announcement.php?delete_id=" + id;
          }
      }
  </script>
  <script src="effect.js"></script>
  </body>
</html>
