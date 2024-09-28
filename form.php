<?php
require_once "conn.php";

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$diseaseOptionsQuery = mysqli_query($conn, "SELECT disease FROM tbldiseaseadvisory");
$diseaseOptions = [];

while ($row = mysqli_fetch_assoc($diseaseOptionsQuery)) {
    $diseaseOptions[] = $row['disease'];
}

if (isset($_POST['addrecordbtn'])) {

    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $midname = $_POST['middlename'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $sex = $_POST['sex'];
    $houseno = $_POST['houseno'];
    $street = $_POST['street'];
    // $barangay = $_POST['barangay'];
    $contact = $_POST['contact'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $disease = $_POST['disease'];

    // Initialize $image_path
    $image_path = "";

    // Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "patientimage/" . $image_name;
        move_uploaded_file($image_tmp, $image_path);
    }

    // Check if the selected disease is "Others"
    if ($disease === 'Others') {
        // Get the specified other disease from the form
        $otherDisease = $_POST['otherDisease'];

        // Check if the other disease already exists in tbldiseaseadvisory
        $checkDuplicateQuery = mysqli_query($conn, "SELECT * FROM tbldiseaseadvisory WHERE disease = '$otherDisease'");

        if (mysqli_num_rows($checkDuplicateQuery) == 0) {
            // Insert the other disease into tbldiseaseadvisory
            $insertOtherDisease = mysqli_query($conn, "INSERT INTO tbldiseaseadvisory (disease) VALUES ('$otherDisease')");

            if (!$insertOtherDisease) {
                echo "<script>alert('Error adding other disease to the advisory table!');</script>";
                exit();
            }
        }

        // Update the $disease variable with the newly inserted disease
        $disease = $otherDisease;
    }

    // Inserting data into the tblrecord table
    $sql = mysqli_query($conn, "INSERT INTO tblrecord (firstname, lastname, middlename, age, height, weight, sex, houseno, street, contact, month, year, disease, image) VALUES ('$fname', '$lname', '$midname', '$age', '$height', '$weight', '$sex', '$houseno', '$street', '$contact', '$month', '$year', '$disease', '$image_path')");

    if ($sql) {
        echo "<script>alert('Record added successfully!');</script>";
        echo "<script>document.location='records.php';</script>";
    } else {
        echo "<script>alert('Something went wrong!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Patient Entry</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('select[name="disease"]').on('change', function () {
                var selectedDisease = $(this).val();
                if (selectedDisease === 'Others') {
                    $('#otherDiseaseTextbox').show();
                } else {
                    $('#otherDiseaseTextbox').hide();
                }
            });
        });
    </script>
  </head>
  <body>
    <?php include 'admin-header.php'; ?>
    <?php include 'admin-sidebar.php'; ?>
    <div class="spacing">
      <div class="record-form">
        <center><h1>ADD NEW RECORD</h1></center>
        <form method = "POST" enctype="multipart/form-data">
          <label for="fname">First name:</label><br>
          <input type="text" placeholder="First name" name="firstname" required><br>

          <label for="midname">Middle name:</label><br>
          <input type="text" placeholder="Middle name" name="middlename" required>

          <label for="lname">Last name:</label><br>
          <input type="text" placeholder="Last name" name="lastname" required>

          <label for="age">Age:</label><br>
          <input type="text" placeholder="Age" name="age" required>

          <label for="height">Height:</label><br>
          <input type="text" placeholder="Height(cm)" name="height">

          <label for="weight">Weight:</label><br>
          <input type="text" placeholder="Weight(kg)" name="weight">

          <label for="sex">Sex:</label><br>
          <select name="sex" class="sex" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>

          <label for="houseno">House no.:</label><br>
          <input type="text" placeholder="House number" name="houseno" required><br>

          <label for="street">Purok:</label><br>
          <select name="street" class="purok" required>
            <option value="Purok 1">Purok 1</option>
            <option value="Purok 2">Purok 2</option>
            <option value="Purok 3">Purok 3</option>
            <option value="Purok 4">Purok 4</option>
            <option value="Purok 5">Purok 5</option>
            <option value="Purok 6">Purok 6</option>
            <option value="Purok 7">Purok 7</option>
          </select>

          <!-- <label for="barangay">Barangay:</label><br>
          <input type="text" placeholder="Barangay" name="barangay"><br> -->

          <label for="contact">Contact Number:</label><br>
          <input type="text" placeholder="Contact number" name="contact" required><br>

          <label for="image">Image:</label><br>
          <input type="file" name="image" accept="image/*"><br><br>

          <center><h1>DISEASE RECORD</h1></center>

          <label for="month">Month of Occurence:</label>
          <select name="month" class="month" required>
            <option value="">Select a month</option>
            <option value="January">January</option>
            <option value="February">February</option>
            <option value="March">March</option>
            <option value="April">April</option>
            <option value="May">May</option>
            <option value="June">June</option>
            <option value="July">July</option>
            <option value="August">August</option>
            <option value="September">September</option>
            <option value="October">October</option>
            <option value="November">November</option>
            <option value="December">December</option>
          </select>

          <label for="year">Year of Occurence:</label>
          <select name="year" class="year" required>
            <option value="">Select a year</option>
            <option value="2000">2000</option>
              <option value="2001">2001</option>
              <option value="2002">2002</option>
              <option value="2003">2003</option>
              <option value="2004">2004</option>
              <option value="2005">2005</option>
              <option value="2006">2006</option>
              <option value="2007">2007</option>
              <option value="2008">2008</option>
              <option value="2009">2009</option>
              <option value="2010">2010</option>
              <option value="2011">2011</option>
              <option value="2012">2012</option>
              <option value="2013">2013</option>
              <option value="2014">2014</option>
              <option value="2015">2015</option>
              <option value="2016">2016</option>
              <option value="2017">2017</option>
              <option value="2018">2018</option>
              <option value="2019">2019</option>
              <option value="2020">2020</option>
              <option value="2021">2021</option>
              <option value="2022">2022</option>
              <option value="2023">2023</option>
              <option value="2024">2024</option>
              <option value="2025">2025</option>
              <option value="2026">2026</option>
              <option value="2027">2027</option>
              <option value="2028">2028</option>
              <option value="2029">2029</option>
              <option value="2030">2030</option>
          </select>

          <label for="disease">Disease Name:</label>
          <select name="disease" class="year" required>
              <option value="">Select a disease</option>
              <?php
              foreach ($diseaseOptions as $option) {
                  echo "<option value=\"$option\">$option</option>";
              }
              ?>
              <option value="Others">Others...</option>
          </select><br>

          <div id="otherDiseaseTextbox" style="display: none;">
              <label for="otherDisease">Specify Other Disease:</label><br>
              <input type="text" name="otherDisease" placeholder="Disease Name">
          </div>
           <br>

          <button type="text" class="addrecordbtn" name="addrecordbtn">Add Record</button>
        </form>
      </div>
    </div>
    <script src="effect.js"></script>
  </body>
</html>
