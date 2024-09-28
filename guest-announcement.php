<?php
include 'conn.php';

if (isset($_POST['upload'])) {
    // Insert into database
    $headline = $conn->real_escape_string($_POST['headline']);
    $description = $conn->real_escape_string($_POST['description']);
    $sql = "INSERT INTO tblannouncement (image, headline, description) VALUES ('$target_file', '$headline', '$description')";

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

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Announcement Board</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-xxxxx" crossorigin="anonymous" />
  </head>
  <body>
    <?php include 'guest-header.php'; ?>
    <?php include 'guest-sidebar.php'; ?>
    <div class="spacing">
      <div class="container">
        <center><h1>ANNOUNCEMENT BOARD</h1></center>
      </div>

      <?php foreach ($announcements as $announcement): ?>
      <div class="container">
        <div class="carousel-content">
            <img src="<?php echo htmlspecialchars($announcement['image']); ?>" alt="Announcement Image" class="carousel-image">
            <div class="carousel-text">
                <h1><?php echo htmlspecialchars($announcement['headline']); ?></h1>
                <p><?php echo htmlspecialchars($announcement['description']); ?></p>
                <p><b>Date Announced: </b><?php echo htmlspecialchars($announcement['date']); ?></p>
            </div>
        </div>
      </div>
      <?php endforeach; ?>

      </div>
    </div>
    <script src="effect.js"></script>
  </body>
</html>
