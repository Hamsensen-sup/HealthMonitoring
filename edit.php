<?php

require_once "conn.php";

session_start(); // Start the session

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // Now you can use $username to display the logged-in user's name or perform other actions.
} else {
    // If the session variable is not set, the user is not logged in; you can redirect them to the login page or take appropriate action.
    header('Location: login.php');
    exit();
}

if (isset($_POST['update'])) {
    $eid = $_GET['editid'];

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
    $disease = $_POST['disease'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    // Image Upload
    $image_path = '';  // Initialize the variable

    // Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "patientimage/" . $image_name;
        move_uploaded_file($image_tmp, $image_path);
    } else {
        // If no file is uploaded, keep the existing image_path
        if(isset($_POST['current_image_path'])) {
            $image_path = $_POST['current_image_path'];
        } else {
            echo "Error uploading file. Error code: " . $_FILES['image']['error'];
        }
    }


    $sql = mysqli_query($conn, "UPDATE tblrecord SET firstname='$fname', lastname='$lname', middlename='$midname', age='$age', height='$height', weight='$weight', sex='$sex', houseno='$houseno', street='$street', contact='$contact', disease='$disease', month='$month', year='$year', image='$image_path' WHERE id='$eid' ");

    if ($sql) {
        echo "<script>alert('You have updated the record successfully!')</script>";
        echo "<script>document.location='records.php'</script>";
    } else {
        echo "<script>alert('Something went wrong!')</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Edit record</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <?php include 'admin-header.php'; ?>
    <?php include 'admin-sidebar.php'; ?>
    <div class="spacing">
      <div class="record-form">
        <span class="closebtn" id="returnButton">&times;</span>
        <center><h1>EDIT INFORMATION</h1></center>
        <form method = "POST" enctype="multipart/form-data">
          <?php
              $eid=$_GET['editid'];
              $sql=mysqli_query($conn,"SELECT * FROM tblrecord WHERE id='$eid'");
              while($row=mysqli_fetch_array($sql)){
          ?>
          <label for="fname">First name:</label><br>
          <input type="text" placeholder="First name" name="firstname" value="<?php echo $row['firstname']?>" required><br>

          <label for="lname">Middle name:</label><br>
          <input type="text" placeholder="Middle name" name="middlename" value="<?php echo $row['middlename']?>" required>

          <label for="lname">Last name:</label><br>
          <input type="text" placeholder="Last name" name="lastname" value="<?php echo $row['lastname']?>" required>

          <label for="lname">Age:</label><br>
          <input type="text" placeholder="Age" name="age" value="<?php echo $row['age']?>" required>

          <label for="height">Height:</label><br>
          <input type="text" placeholder="Height(cm)" name="height" value="<?php echo $row['height']?> cm">

          <label for="weight">Weight:</label><br>
          <input type="text" placeholder="Weight(kg)" name="weight" value="<?php echo $row['weight']?> kg">

          <label for="sex">Sex:</label><br>
          <select name="sex" class="sex" required>
            <option value="<?php echo $row['sex'] ?>"><?php echo $row['sex'] ?></option>
            <option value="Male">Male</option>
            <option value="Male">Female</option>
          </select>

          <label for="fname">House no.:</label><br>
          <input type="text" placeholder="House number" name="houseno" value="<?php echo $row['houseno']?>" required><br>

          <label for="street">Purok:</label><br>
          <select name="street" class="purok" required>
            <option value="<?php echo $row['street'] ?>"><?php echo $row['street'] ?></option>
            <option value="Purok 1">Purok 1</option>
            <option value="Purok 2">Purok 2</option>
            <option value="Purok 3">Purok 3</option>
            <option value="Purok 4">Purok 4</option>
            <option value="Purok 5">Purok 5</option>
            <option value="Purok 6">Purok 6</option>
            <option value="Purok 7">Purok 7</option>
          </select>

          <!-- <label for="fname">Barangay:</label><br>
          <input type="text" placeholder="Barangay" name="barangay" value="<?php echo $row['barangay']?>"><br> -->

          <label for="lname">Contact Number:</label><br>
          <input type="text" placeholder="Contact number" name="contact" value="<?php echo $row['contact']?>" required><br>

          <label for="image">Change Image:</label><br>
          <?php if (!empty($row['image'])) : ?>
              <img src="<?php echo $row['image']; ?>" alt="Current Image" class="profile-img">
              <input type="hidden" name="current_image_path" value="<?php echo $row['image']; ?>">
          <?php endif; ?><br>
          <input type="file" name="image" accept="image/*"><br><br>

          <input type="hidden" name="current_image_path" value="<?php echo $row['image']; ?>">


          <center><h1>DISEASE RECORD</h1></center>

          <label for="month">Month of Occurence:</label>
          <select name="month" class="month">
            <option value="<?php echo $row['month'] ?>"><?php echo $row['month'] ?></option>
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
            <option value="">=====empty record=====</option>
          </select>

          <label for="year">Year of Occurence:</label>
          <select name="year" class="year">
            <option value="<?php echo $row['year'] ?>"><?php echo $row['year'] ?></option>
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
            <option value="">=====empty record=====</option>
          </select>

          <label for="lname">Disease Name:</label><br>
          <input type="text" placeholder="Disease name" name="disease" value="<?php echo $row['disease']?>" ><br>

          <?php } ?>

          <button type="text" class="submitbtn" name="update">Update Record</button>
        </form>
      </div>
    </div>
    <script src="effect.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
          var returnButton = document.getElementById("returnButton");
          returnButton.addEventListener("click", function() {
              history.back(); // Go back to the previous page
          });
      });
    </script>

  </body>
</html>
