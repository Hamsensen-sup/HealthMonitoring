<!DOCTYPE html>
<html>
<head>
  <title>Health Statistics</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'guest-header.php'; ?>
  <?php include 'guest-sidebar.php'; ?>
  <div class="spacing">
    <div class="map-wrapper">
      <i><p>NOT FULLY FUNCTIONAL SAMPLE ONLY!</p></i>
        <div class="map-container" id="draggableContainer">
          <img src="mapsample.png" alt="" id="draggableMap" width="1080px" usemap="#image_map">
          <map name="image_map">
              <area alt="" title="Purok 1" onclick="openModal1();" coords="-3,336 307,252 307,0 9,14 0,52 " shape="polygon">
              <area alt="" title="Purok 2" onclick="openModal2();" coords="379,-3 379,9 434,304 872,237 909,168 857,6 " shape="polygon">
              <area alt="" title="Purok 3" onclick="openModal3();" coords="478,492 466,741 701,721 727,611 912,597 909,515 553,562 " shape="polygon">
              <area alt="" title="Purok 4" onclick="openModal4();" coords="1578,0 1575,214 2134,264 2132,3 2132,0 " shape="polygon">
          </map>
        </div>
        <a href="guest-mapping.php"><button type="button" name="button" class="defaultbtn">Simple View</button></a>
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

  <script>
    function openModal1() {
      fetch('purok1-modal.php')
      .then(response => response.text())
      .then(data => {
        document.getElementById('modal1-content').innerHTML = data;
        document.getElementById('modal1').style.display = 'block';
      });
    }

    function openModal2() {
      fetch('purok2-modal.php')
      .then(response => response.text())
      .then(data => {
        document.getElementById('modal2-content').innerHTML = data;
        document.getElementById('modal2').style.display = 'block';
      });
    }

    function openModal3() {
      fetch('purok3-modal.php')
      .then(response => response.text())
      .then(data => {
        document.getElementById('modal3-content').innerHTML = data;
        document.getElementById('modal3').style.display = 'block';
      });
    }

    function openModal4() {
      fetch('purok4-modal.php')
      .then(response => response.text())
      .then(data => {
        document.getElementById('modal4-content').innerHTML = data;
        document.getElementById('modal4').style.display = 'block';
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
  // Event listeners for both modals
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

  </script>
  <script src="drag.js"></script>
  <script src="effect.js"></script>
</body>
</html>
