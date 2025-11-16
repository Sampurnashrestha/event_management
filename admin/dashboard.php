<?php
session_start();
include "../connection.php";

// Uncomment for real session check
// if (!isset($_SESSION['admin'])) {
//     header("Location: login.php");
//     exit();
// }

// Fetch counts
$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_users FROM users"))['total_users'];
$eventCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_events FROM events"))['total_events'];
$bookingCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_bookings FROM bookings"))['total_bookings'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="./admin_main.css">
  
</head>
<body>
  <div class="admin-navbar">
    <h2>Admin Dashboard</h2>
    <ul>
      <li><a href="users.php">Manage Users</a></li>
      <li><a href="events.php">Manage Events</a></li>
      <li><a href="bookings.php">View Bookings</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="admin-content">
    <h3>Welcome, Admin 👋</h3>
    <p>Use the menu to manage events, users, and bookings.</p>

    <div class="cards">
      <div class="card">
        <h2><?= $userCount ?></h2>
        <p>Total Users</p>
      </div>
      <div class="card">
        <h2><?= $eventCount ?></h2>
        <p>Total Events</p>
      </div>
      <div class="card">
        <h2><?= $bookingCount ?></h2>
        <p>Total Bookings</p>
      </div>
    </div>
  </div>
</body>
</html>
