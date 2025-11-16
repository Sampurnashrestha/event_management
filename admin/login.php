<?php
session_start();
include("../connection.php");

$error = "";

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Static admin login
  $checkEmail = "admin@gmail.com";
  $checkPassword = "password";

  if ($email === $checkEmail && $password === $checkPassword) {
    header("Location: dashboard.php");
    exit();
  } else {
    $error = "Invalid email or password!";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <link rel="stylesheet" href="./admin_login.css">
</head>
<body>
  <div class="login-container">
    <h2>Admin Login</h2>

    <?php if (!empty($error)) : ?>
      <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit" name="login">Login</button>
    </form>
  </div>
</body>
</html>
