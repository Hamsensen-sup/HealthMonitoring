<!DOCTYPE html>
<html>
<head>
  <title>Health Statistics</title>
  <link rel="stylesheet" href="styles.css">
  <style>
  .container {
    display: flex;
    justify-content: space-between;
  }

  #map {
    width: 70%; /* Adjust the width of the map as needed */
    height: 150px;
  }

  .note-text {
    width: 30%; /* Adjust the width of the note text as needed */
  }
</style>
</head>
<body>
  <?php include 'guest-header.php'; ?>
  <?php include 'guest-sidebar.php'; ?>
  <div class="spacing">
    <div class="map-container">
      <center><h1>BARANGAY HEALTH MAPPING</h1></center>
    </div>
    <div class="container">

      <div id="map" style="width: 800px; height: 450px;"></div>
      <div class="note-text ">
        <a href="map-dashboard-guest.php" target="_blank"><button type="button" name="button">See overall data</button></a>
        <br><br><br>
        <p><b>NOTE: </b>This map displays the layout of Barangay San Pedro, showcasing its various 'purok' or clusters. Clicking on a marker reveals information about the top 5 disease cases in that area, along with related advisories and the age range of individuals that is affected by each disease.</p>
        <p>Users can utilize the interactive features of the map to access real-time updates on disease prevalence, enabling a comprehensive understanding of the health dynamics within Barangay San Pedro and facilitating informed decision-making for residents and local authorities alike.</p>
      </div>
    </div>
  </div>

  <div id="modal1" class="modal">
    <div class="modal-content" id="modal1-content"></div>
  </div>

  <div id="modal2" class="modal">
    <div class="modal-content" id="modal2-content"></div>
  </div>

  <div id="modal3" class="modal">
    <div class="modal-content" id="modal3-content"></div>
  </div>

  <div id="modal4" class="modal">
    <div class="modal-content" id="modal4-content"></div>
  </div>

  <div id="modal5" class="modal">
    <div class="modal-content" id="modal5-content"></div>
  </div>

  <div id="modal6" class="modal">
    <div class="modal-content" id="modal6-content"></div>
  </div>

  <div id="modal7" class="modal">
    <div class="modal-content" id="modal7-content"></div>
  </div>

  <script>
  function openModal(modalId, modalContentId, modalContentUrl) {
    fetch(modalContentUrl)
    .then(response => response.text())
    .then(data => {
      document.getElementById(modalContentId).innerHTML = data;
      document.getElementById(modalId).style.display = 'block';
    });
  }

    // Function to close the modal when clicking outside
    function closeModal(event, modalId) {
      var modal = document.getElementById(modalId);
      // If the target (clicked element) is the modal itself and not the content, close the modal
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    }

    document.getElementById('modal1').addEventListener('click', function(event) {
      closeModal(event, 'modal1');
    });

    document.getElementById('modal2').addEventListener('click', function(event) {
      closeModal(event, 'modal2');
    });

    document.getElementById('modal3').addEventListener('click', function(event) {
      closeModal(event, 'modal3');
    });

    document.getElementById('modal4').addEventListener('click', function(event) {
      closeModal(event, 'modal4');
    });

    document.getElementById('modal5').addEventListener('click', function(event) {
      closeModal(event, 'modal5');
    });

    document.getElementById('modal6').addEventListener('click', function(event) {
      closeModal(event, 'modal6');
    });

    document.getElementById('modal7').addEventListener('click', function(event) {
      closeModal(event, 'modal7');
    });
  </script>
  <script src="drag.js"></script>
  <script src="effect.js"></script>
  <script>
    function initMap() {
      var markerLatitude1 = 14.840839;
      var markerLongitude1 = 120.763378;

      var markerLatitude2 = 14.840397;
      var markerLongitude2 = 120.761910;

      var markerLatitude3 = 14.839162;
      var markerLongitude3 = 120.759642;

      var markerLatitude4 = 14.838940;
      var markerLongitude4 = 120.756652;

      var markerLatitude5 = 14.841046;
      var markerLongitude5 = 120.753566;

      var markerLatitude6 = 14.843582;
      var markerLongitude6 = 120.763028;

      var markerLatitude7 = 14.83902;
      var markerLongitude7 = 120.76323;

      var markerLatitude8 = 14.841541704498528;
      var markerLongitude8 = 120.75948632877557;

      var bounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(14.837363247286978, 120.75144260767641), // Southwest corner
        new google.maps.LatLng(14.847220208545172, 120.7652884389731)  // Northeast corner
      );

      var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: markerLatitude1, lng: markerLongitude3 },
        zoom: 16,
        restriction: {
          latLngBounds: bounds,
          strictBounds: false
        }
      });

      var customIcon = {
          url: 'custom-marker.png',
          scaledSize: new google.maps.Size(40, 70),
      };

      var marker = new google.maps.Marker({
        position: { lat: markerLatitude1, lng: markerLongitude1 },
        map: map,
        icon: customIcon,
        label: {
          text: "Purok 1",
          color: "white",
          fontSize: "20px",
          fontWeight: "bold",
          className: "custom-label"
        }
      });

      var marker2 = new google.maps.Marker({
        position: { lat: markerLatitude2, lng: markerLongitude2 },
        map: map,
        icon: customIcon,
        label: {
          text: "Purok 2",
          color: "white",
          fontSize: "20px",
          fontWeight: "bold",
          className: "custom-label"
        }
      });

      var marker3 = new google.maps.Marker({
        position: { lat: markerLatitude3, lng: markerLongitude3 },
        map: map,
        icon: customIcon,
        label: {
          text: "Purok 3",
          color: "white",
          fontSize: "20px",
          fontWeight: "bold",
          className: "custom-label"
        },
      });

      var marker4 = new google.maps.Marker({
        position: { lat: markerLatitude4, lng: markerLongitude4 },
        map: map,
        icon: customIcon,
        label: {
          text: "Purok 4",
          color: "white",
          fontSize: "20px",
          fontWeight: "bold",
          className: "custom-label"
        }
      });

      var marker5 = new google.maps.Marker({
        position: { lat: markerLatitude5, lng: markerLongitude5 },
        map: map,
        icon: customIcon,
        label: {
          text: "Purok 5",
          color: "white",
          fontSize: "20px",
          fontWeight: "bold",
          className: "custom-label"
        }
      });

      var marker6 = new google.maps.Marker({
        position: { lat: markerLatitude6, lng: markerLongitude6 },
        map: map,
        icon: customIcon,
        label: {
          text: "Purok 6",
          color: "white",
          fontSize: "20px",
          fontWeight: "bold",
          className: "custom-label"
        }
      });

      var marker7 = new google.maps.Marker({
        position: { lat: markerLatitude7, lng: markerLongitude7 },
        map: map,
        icon: customIcon,
        label: {
          text: "Purok 7",
          color: "white",
          fontSize: "20px",
          fontWeight: "bold",
          className: "custom-label"
        }
      });

      // var marker8 = new google.maps.Marker({
      //   position: { lat: markerLatitude8, lng: markerLongitude8 },
      //   map: map,
      //   label: {
      //     text: "SAN PEDRO",
      //     color: "black",
      //     fontSize: "20px",
      //     fontWeight: "bold", // You can adjust this property
      //   }
      // });

      marker.addListener("click", function () {
        openModal('modal1', 'modal1-content', 'purok1-modal.php');
      });

      marker2.addListener("click", function () {
        openModal('modal2', 'modal2-content', 'purok2-modal.php');
      });

      marker3.addListener("click", function () {
        openModal('modal3', 'modal3-content', 'purok3-modal.php');
      });

      marker4.addListener("click", function () {
        openModal('modal4', 'modal4-content', 'purok4-modal.php');
      });

      marker5.addListener("click", function () {
        openModal('modal5', 'modal5-content', 'purok5-modal.php');
      });

      marker6.addListener("click", function () {
        openModal('modal6', 'modal6-content', 'purok6-modal.php');
      });

      marker7.addListener("click", function () {
        openModal('modal7', 'modal7-content', 'purok7-modal.php');
      });

    }

  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDREw4jyiWNXNTEZVh0P4IYac-BB3y9OWo&callback=initMap"></script>
</body>
</html>
