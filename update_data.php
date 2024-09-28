<?php
include 'conn.php';
include 'map-function.php';

// Get the selected year from the query parameter
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date("Y");

// Assuming you have a function to get the top diseases for a specific year
$topDiseases = fetchTopDiseases($selectedPurok, $selectedYear);

// Calculate total cases
$totalCases = array_sum(array_column($topDiseases, 'cases'));

// Other necessary code for fetching advisories and rendering the HTML content

// Output the updated HTML content
?>
<h1><?php echo $selectedPurok; ?></h1>
<div class="map-stat">
    <p>Total disease cases: <?php echo $totalCases; ?> cases</p>
    <b>
        <p>This table shows the top 5 diseases that exist in <?php echo $selectedPurok; ?>:</p>
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
</div>
