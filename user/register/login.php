<?php
include('../../connection.php'); // adjust the path

session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['user'] = $email;
        echo "<script>alert('Login successful'); window.location.href='../index.php';</script>";
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="login_main.css">
</head>
<body>
<div class="auth-container">
    <form action="" method="POST" class="auth-box">
        <h2>Login</h2>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <p>Don’t have an account? <a href="signup.php">Sign up here</a></p>
    </form>
</div>
</body>
</html>
