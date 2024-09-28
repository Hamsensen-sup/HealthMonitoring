<?php
include 'conn.php';


$puroks = array();

$query = "SELECT DISTINCT street FROM tblrecord ORDER BY CAST(SUBSTRING(street, 7) AS UNSIGNED)";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    $puroks[] = $row;
}

mysqli_free_result($result);

function getMostAffectedAgeGroup($disease, $selectedPurok) {
    global $conn;

    $query = "SELECT age, COUNT(age) as ageCount
              FROM tblrecord
              WHERE street = ? AND disease = ?
              GROUP BY age
              ORDER BY ageCount DESC
              LIMIT 1";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $selectedPurok, $disease);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $age = $row['age'];

    if ($age <= 12) return "Children (0-12 years)";
    if ($age <= 19) return "Teenage (13-19 years)";
    if ($age <= 39) return "Adult (20-39 years)";
    if ($age <= 64) return "Middle Age (40-64 years)";
    return "Old (65 years and above)";
}

function fetchTopDiseases($selectedPurok) {
    global $conn;

    $diseases = array();

    $query = "SELECT disease, COUNT(disease) as cases, MAX(sex) as mostAffectedGender
              FROM tblrecord
              WHERE street = ?
              GROUP BY disease
              ORDER BY cases DESC LIMIT 5";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedPurok);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $diseaseName = $row['disease'];
        $row['mostAffectedAgeGroup'] = getMostAffectedAgeGroup($diseaseName, $selectedPurok);
        $diseases[] = $row;
    }

    return $diseases;
}


$selectedPurok = strtoupper($puroks[0]['street']);  // Default value
ensurePurokInAdvisory($selectedPurok);  // Add this line

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['puroklist'])) {
    $selectedPurok = $_POST['puroklist'];
    ensurePurokInAdvisory($selectedPurok);
}


$topDiseases = fetchTopDiseases($selectedPurok);

function fetchTotalDiseaseCases($selectedPurok) {
    global $conn;
    $query = "SELECT COUNT(disease) as totalCases FROM tblrecord WHERE street = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedPurok);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['totalCases'];
}

$totalCases = fetchTotalDiseaseCases($selectedPurok);

function fetchAdvisory($selectedPurok) {
    global $conn;
    $query = "SELECT advisory FROM tbladvisory WHERE street = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedPurok);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['advisory'] : null; // Return null if there's no row
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['advisory'])) {
    $query = "INSERT INTO tbladvisory (street, advisory) VALUES (?, ?) ON DUPLICATE KEY UPDATE advisory = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $selectedPurok, $_POST['advisory'], $_POST['advisory']);
    $stmt->execute();
    $stmt->close();
}


$advisory = fetchAdvisory($selectedPurok);

function ensurePurokInAdvisory($purok) {
    global $conn;
    $query = "SELECT 1 FROM tbladvisory WHERE street = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $purok);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $stmt->close();

        // If the purok doesn't exist, insert it
        $insertQuery = "INSERT INTO tbladvisory (street, advisory) VALUES (?, '')";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("s", $purok);
        $insertStmt->execute();
        $insertStmt->close();
    }
}

function getAdvisoryForDisease($disease)
{
    global $conn;

    // Assuming you have a table named tbldiseaseadvisory with columns disease and advisory
    $query = "SELECT advisory FROM tbldiseaseadvisory WHERE disease = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $disease);

    if ($stmt->execute()) {
        $stmt->bind_result($advisory);
        $stmt->fetch();
        $stmt->close();
        return $advisory ? $advisory : 'No advisory available';
    } else {
        // Handle the case where the query fails
        return 'Error retrieving advisory';
    }
}

function getInterventionForDisease($disease)
{
    global $conn;

    $query = "SELECT intervention FROM tbldiseaseadvisory WHERE disease = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $disease);

    if ($stmt->execute()) {
        $stmt->bind_result($intervention);
        $stmt->fetch();
        $stmt->close();
        return $intervention ? $intervention : 'No intervention information available';
    } else {
        // Handle the case where the query fails
        return 'Error retrieving intervention information';
    }
}

?>
