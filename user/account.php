<?php
include('../connection.php'); // ✅ correct path (one folder up)
session_start();

// ✅ Check if user is logged in
if (!isset($_SESSION['user'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user'];

// ✅ Fetch user info by email
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$user_email'");
$user = mysqli_fetch_assoc($query);

// ✅ Update account info
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $password = md5($password);
        $update = mysqli_query($conn, "UPDATE users SET username='$username', email='$email', password='$password' WHERE email='$user_email'");
    } else {
        $update = mysqli_query($conn, "UPDATE users SET username='$username', email='$email' WHERE email='$user_email'");
    }

    if ($update) {
        // Update session if email changed
        $_SESSION['user'] = $email;
        echo "<script>alert('Account updated successfully!'); window.location='account.php';</script>";
    } else {
        echo "<script>alert('Error updating account');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="./user_main.css">
    <link rel="stylesheet" href="./nav.css">
    <style>
        .account_section{
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
        }

        .account-box {
            width: 420px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 40px 35px;
            color: #fff;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            animation: fadeUp 0.8s ease-out forwards;
        }

        /* ===== HEADING ===== */
        .account-box h2 {
            font-size: 1.8rem;
            color: #fff000;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* ===== FORM ===== */
        .account-box form {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }

        .account-box label {
            text-align: left;
            font-weight: 500;
            color: #fff;
            margin-bottom: -8px;
        }

        .account-box input {
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .account-box input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .account-box input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
            border-color: #fff000;
            transform: scale(1.02);
        }

        /* ===== BUTTON ===== */
        .account-box button {
            padding: 12px;
            background-color: #fff000;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            color: #000;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .account-box button:hover {
            background-color: #ff4d4d;
            color: #fff;
            transform: translateY(-3px);
        }

        /* ===== LOGOUT LINK ===== */
        .logout {
            margin-top: 20px;
        }

        .logout a {
            color: #ffcb42;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .logout a:hover {
            color: #fff000;
        }

        /* ===== ANIMATION ===== */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 500px) {
            .account-box {
                width: 90%;
                padding: 30px 25px;
            }

            .account-box h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
     <nav class="navbar">
    <div class="logo">
      <a href="index.html">Hamro<span> Event</span></a>
    </div>

    <ul class="nav-links">
      <li><a href="index.php" >Home</a></li>
      <li><a href="events.html">Events</a></li>
      <li><a href="index.php#about">About</a></li>
      <li><a href="index.php#contact">Contact</a></li>
    </ul>


    <div class="account" tabindex="0">
      <a href="#" class="account-icon"><i class="fa-solid fa-user-circle"></i></a>
      <ul class="account-dropdown">
        <li><a href="account.php">Profile</a></li>
        <li><a href="../user/register/logout.php">Logout</a></li>
      </ul>
    </div>
  </nav>
    <section class="account_section">
        <div class="account-box">
            <h2>My Account</h2>
            <form method="POST">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label>New Password (optional)</label>
                <input type="password" name="password" placeholder="Enter new password if you want to change">

                <button type="submit" name="update">Update</button>
            </form>
    </section>

    </div>
</body>

</html>