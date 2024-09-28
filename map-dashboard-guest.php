<?php
include 'conn.php';

include 'map-function.php';

$selectedPurok = strtoupper($puroks[0]['street']);  // Default value
$selectedYear = date("Y");  // Default value for the current year

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['puroklist'])) {
        $selectedPurok = $_POST['puroklist'];
        ensurePurokInAdvisory($selectedPurok);
    }
    if (isset($_POST['yearlist'])) {
        $selectedYear = $_POST['yearlist'];
        ensurePurokInAdvisory($selectedPurok);
    }
}

$topDiseases = fetchTopDiseases($selectedPurok, $selectedYear);
$totalCases = fetchTotalDiseaseCases($selectedPurok, $selectedYear);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Health Mapping Data</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .dropdown-container {
            display: flex;
            justify-content: flex-start;
        }
    </style>
</head>

<body>
    <?php include 'guest-header.php'; ?>
    <?php include 'guest-sidebar.php'; ?>
    <div class="spacing">
        <div class="container">
            <center>
                <h1>MAP DATA</h1>
            </center>
        </div>
        <div class="guest-content">
            <!-- Wrapping the select inside the form -->
            <div class="dropdown-container">
              <form method="post" action="" id="combinedForm" onsubmit="return submitCombinedForm()">
                  <div class="dropdown-container">
                      <select class="list-filter" name="puroklist" id="purokDropdown" onchange="submitCombinedForm()">
                          <?php foreach ($puroks as $purok): ?>
                              <option value="<?php echo htmlspecialchars(strtoupper($purok['street'])); ?>" <?php echo (strtoupper($purok['street']) === $selectedPurok) ? 'selected' : ''; ?>>
                                  <?php echo htmlspecialchars(strtoupper($purok['street'])); ?>
                              </option>
                          <?php endforeach; ?>
                      </select>

                      <select class="list-filter" name="yearlist" id="yearDropdown" onchange="submitCombinedForm()">
                          <?php
                          // Generating year options dynamically
                          $currentYear = date("Y");
                          for ($year = 2000; $year <= $currentYear; $year++) {
                              echo "<option value=\"$year\" " . (($year == $selectedYear) ? 'selected' : '') . ">$year</option>";
                          }
                          ?>
                      </select>
                  </div>
              </form>

            </div>
            <center>
                <h1><?php echo $selectedPurok; ?></h1>
            </center>
            <div class="map-stat">
                <p>Total disease cases: <?php echo $totalCases; ?> cases</p>
                <b><p>This table shows the top 5 diseases that exist in <?php echo $selectedPurok; ?> for year
                <?php echo ($selectedYear == date("Y")) ? "2023" : $selectedYear; ?>:</p></b>
            </div>
            <table class="map-table">
                <tr>
                    <th>#</th>
                    <th>Disease</th>
                    <th>Cases</th>
                    <th>Most affected age group</th>
                    <th>Most affected gender</th>
                </tr> <?php foreach ($topDiseases as $index => $disease): ?> <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($disease['disease']); ?></td>
                    <td><?php echo htmlspecialchars($disease['cases']); ?></td>
                    <td><?php echo htmlspecialchars($disease['mostAffectedAgeGroup']); ?></td>
                    <td><?php echo htmlspecialchars($disease['mostAffectedGender']); ?></td>
                </tr> <?php endforeach; ?>
            </table>
            <div class="map-stat">
                <h2>Health advisory: </h2>
                <?php
                if (!empty($topDiseases)) {
                    $advisoryText = getAdvisoryForDisease($topDiseases[0]['disease']);
                    echo ($advisoryText !== null) ? "<p>$advisoryText</p>" : "<p>No available health advisory</p>";
                } else {
                    echo "<p>No available health advisory</p>";
                }
                ?>
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
            <!-- <script src="map-dashboard.js"></script> -->
        </div>
        <script src="effect.js"></script>
        <script>
        function submitCombinedForm() {
            document.getElementById("combinedForm").submit();
            return false; // Prevent default form submission
        }

        </script>
    </div>
</body>

</html>
