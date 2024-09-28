<?php
include 'conn.php';
include 'map-function-guest.php';

$selectedPurok = 'Purok 7';
$topDiseases = fetchTopDiseases($selectedPurok);


// Calculate total cases
$totalCases = array_sum(array_column($topDiseases, 'cases'));

$selectedPurok = 'Purok 7';

// I'm making an assumption that you have a function or method to get an advisory for a given Purok
$advisoryQuery = "SELECT advisory FROM tbladvisory WHERE street = ?";
$stmt = $conn->prepare($advisoryQuery);
$stmt->bind_param("s", $selectedPurok);
$stmt->execute();
$advisoryResult = $stmt->get_result();
$advisoryData = $advisoryResult->fetch_assoc();
$advisory = $advisoryData ? $advisoryData['advisory'] : '';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Health Mapping Data</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="modal-container">
      <h1><?php echo $selectedPurok; ?></h1>
      <div class="map-stat">
          <p>Total disease cases: <?php echo $totalCases; ?> cases</p>
          <b>
              <p>This table shows the top 5 diseases that exist in <?php echo $selectedPurok; ?> as of <?php echo date("Y"); ?>:</p>
          </b>
      </div>
      <table class="map-table">
          <tr>
              <th>#</th>
              <th>Disease</th>
              <th>Cases</th>
              <th>Most affected age group</th>
              <th>Most affected gender</th>
          </tr>
          <?php foreach ($topDiseases as $index => $disease): ?>
          <tr>
              <td><?php echo $index + 1; ?></td>
              <td><?php echo htmlspecialchars($disease['disease']); ?></td>
              <td><?php echo htmlspecialchars($disease['cases']); ?></td>
              <td><?php echo htmlspecialchars($disease['mostAffectedAgeGroup']); ?></td>
              <td><?php echo htmlspecialchars($disease['mostAffectedGender']); ?></td>
          </tr>
          <?php endforeach; ?>
      </table>
      <div class="map-stat">
          <h2>Health advisory: </h2>
          <div id="advisoryDisplay">
               <p><?php echo getAdvisoryForDisease($topDiseases[0]['disease']); ?></p>
          </div>
          <h2>Barangay treatment/intervention plan: </h2>
          <?php
          if (!empty($topDiseases)) {
              $interventionText = getInterventionForDisease($topDiseases[0]['disease']);
              echo ($interventionText !== null) ? "<p>$interventionText</p>" : "<p>No intervention information</p>";
          } else {
              echo "<p>No intervention information</p>";
          }
          ?>
      </div>

  </div>
</body>

</html>
