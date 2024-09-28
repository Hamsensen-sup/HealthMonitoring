<?php
include 'conn.php';
session_start(); // Start the session

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements with question mark placeholders
    $query = "SELECT * FROM adminusers WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Verify the password using password_verify
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Successful login, set a session variable
            $_SESSION['username'] = $username;

            header('Location: records.php');
            exit();
        } else {
            // Incorrect password
            $error_message = 'Incorrect username or password. Please try again.';
        }
    } else {
        // Incorrect username
        $error_message = 'Incorrect username or password. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <!-- Your existing script for countdown can remain if you want to keep it for other purposes -->
    <script>
        function startCountdown(seconds) {
            // ... (your existing countdown logic)
        }
    </script>
</head>
<body>
<?php include 'guest-header.php'; ?>
<div class="login-page">
    <div class="form-container">
        <form action="login.php" method="post">
            <center><h1>HEALTHCARE WORKER LOG IN</h1></center>
            <input type="text" name="username" placeholder="Username" required class="box">
            <input type="password" name="password" placeholder="Password" required class="box">

            <?php
            if (isset($error_message)) {
                echo '<p style="color: red;">' . $error_message . '</p>';
            }
            ?>

            <input type="submit" name="submit" value="LOG IN" class="btn">
        </form>
    </div>
</div>
<script src="effect.js"></script>
</body>
</html>
