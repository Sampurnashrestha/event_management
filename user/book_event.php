<?php
session_start();
include "../connection.php";

if (!isset($_GET['event_id'])) {
    echo "<script>alert('Invalid event!'); window.location='event.php';</script>";
    exit();
}

$event_id = intval($_GET['event_id']);

// Fetch event details
$event_query = mysqli_query($conn, "SELECT * FROM events WHERE id='$event_id'");
$event = mysqli_fetch_assoc($event_query);

if (!$event) {
    echo "<script>alert('Event not found!'); window.location='event.php';</script>";
    exit();
}

if (isset($_POST['submit'])) {
    $event_type = mysqli_real_escape_string($conn, $_POST['event_type']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $total_person = intval($_POST['total_person']);
    $price = floatval($_POST['price']);
    $date = $_POST['date'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // PRICE LIMIT
    if ($price > 100000) {
        echo "<script>alert('Price cannot exceed 100,000'); window.history.back();</script>";
        exit();
    }

    // CHECK IF DATE ALREADY BOOKED FOR THIS EVENT
    $check = mysqli_query($conn, "SELECT * FROM bookings WHERE event_id='$event_id' AND date='$date'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Sorry! This event is already booked on that date.'); window.history.back();</script>";
        exit();
    }

    // FIXED INSERT QUERY (Removed event_category)
    $query = "INSERT INTO bookings (event_id, event_type, name, email, phone, total_person, price, date, message)
              VALUES ('$event_id', '$event_type', '$name', '$email', '$phone', '$total_person', '$price', '$date', '$message')";

    if (mysqli_query($conn, $query)) {
        mysqli_query($conn, "UPDATE events SET availability_status='booked' WHERE id='$event_id'");
        echo "<script>alert('Booking confirmed successfully!'); window.location.href='event.php';</script>";
    } else {
        echo "<script>alert('Error: Could not save booking'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Event | Hamro Event</title>
<link rel="stylesheet" href="nav.css">
<link rel="stylesheet" href="user_main.css">

<style>
.booking {
  height: 100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  color:white;
  background:rgba(0,0,0,0.4);
}
.booking-container {
  background:rgba(255,255,255,0.1);
  padding:40px 30px;
  border-radius:15px;
  box-shadow:0 5px 15px rgba(0,0,0,0.4);
  width:400px;
  backdrop-filter:blur(10px);
}
h2 { color: #f9d342; margin-bottom:20px; text-align:center;}
.form-group { margin-bottom:15px;}
label {display:block;font-size:14px;margin-bottom:5px;color:#f9d342;}
input, textarea, select {
  width:100%; padding:10px; border-radius:8px; border:none;
  background: rgba(255,255,255,0.2); color:white; font-size:14px; outline:none;
}
textarea{height:80px; resize:none;}
button {
  width:100%; padding:12px; border:none; border-radius:25px;
  background:#f9d342; color:black; font-weight:600; cursor:pointer; margin-top:10px;
}
button:hover {background:white;color:black;}
.back-btn {display:block;text-align:center;margin-top:10px;color:#f9d342;text-decoration:none;font-size:14px;}
.back-btn:hover {text-decoration:underline;}
</style>
</head>
<body>

<nav class="navbar">
    <div class="logo"><a href="index.php">Hamro<span> Event</span></a></div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="event.php">Events</a></li>
        <li><a href="book_event.php" class="active">Booking</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
</nav>

<section class="booking">
  <div class="booking-container">
    <h2><i class="fa-solid fa-calendar-check"></i> Book Your Event</h2>

    <form method="POST">

      <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

      <div class="form-group">
        <label>Event Type</label>
        <select name="event_type" required>
          <option value="">Select Event Type</option>
          <option value="Birthday">Birthday</option>
          <option value="Marriage">Marriage</option>
          <option value="Engagement">Engagement</option>
        </select>
      </div>

      <div class="form-group">
        <label>Total Persons</label>
        <input type="number" name="total_person" min="1" max="500" required>
      </div>

      <div class="form-group">
        <label>Price</label>
        <input type="number" name="price" max="100000" value="<?= $event['price'] ?>" required>
      </div>

      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" required>
      </div>

      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" required>
      </div>

      <div class="form-group">
        <label>Phone Number</label>
        <input type="text" name="phone" required>
      </div>

      <div class="form-group">
        <label>Event Date</label>
        <input type="date" name="date" required min="<?= date('Y-m-d') ?>">
      </div>

      <div class="form-group">
        <label>Message (Optional)</label>
        <textarea name="message"></textarea>
      </div>

      <button type="submit" name="submit">Confirm Booking</button>
    </form>

    <a href="event.php" class="back-btn">← Back to Events</a>
  </div>
</section>

</body>
</html>
