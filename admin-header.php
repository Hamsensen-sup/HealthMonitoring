<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Header</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
  <div class="header">
    <h1>Barangay San Pedro Health Monitoring System</h1>
    <button class="user-button" onclick="openModal()"><i class="fas fa-user"></i></button>
  </div>

  <div id="myModal" class="logoutModal">
    <div class="logoutmodal-box">
      <p>Are you sure you want to logout?</p>
      <button class="logout-button" onclick="logout()">Logout</button>
    </div>
  </div>

  <script>
    function openModal() {
      var modal = document.getElementById("myModal");
      modal.style.display = "block";
    }

    function logout() {
      // Send an AJAX request to a PHP script to destroy the session and log the user out.
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "logout.php", true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Reload the page or redirect to the login page upon successful logout.
          window.location.reload();
        }
      };
      xhr.send();
    }

    window.onclick = function(event) {
      var modal = document.getElementById("myModal");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
