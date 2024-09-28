<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <?php

    include 'admin-header.php';
    session_start(); // Start the session

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        // Now you can use $username to display the logged-in user's name or perform other actions.
    } else {
        // If the session variable is not set, the user is not logged in; you can redirect them to the login page or take appropriate action.
        header('Location: login.php');
        exit();
    }

    ?>
    <?php include 'admin-sidebar.php'; ?>
    <div class="spacing">
      <div class = "admin-content">
        <h1>ADMIN PAGE</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
          incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>

    </div>
    <script src="effect.js"></script>
  </body>
</html>
