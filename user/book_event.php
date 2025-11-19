<?php
session_start();
include "../connection.php";

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    echo "<script>alert('Invalid request!'); window.location='event.php';</script>";
    exit();
}

$item_id = intval($_GET['id']);
$item_type = $_GET['type'];

// Fetch correct table
if ($item_type === "venue") {
    $fetch = mysqli_query($conn, "SELECT * FROM venue WHERE id='$item_id'");
} elseif ($item_type === "catering") {
    $fetch = mysqli_query($conn, "SELECT * FROM catering WHERE id='$item_id'");
} else {
    echo "<script>alert('Invalid type!'); window.location='event.php';</script>";
    exit();
}

$event = mysqli_fetch_assoc($fetch);

if (!$event) {
    echo "<script>alert('Item not found!'); window.location='event.php';</script>";
    exit();
}

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $total_person = intval($_POST['total_person']);
    $price = floatval($_POST['price']);
    $date = $_POST['date'];
    $time_slot = mysqli_real_escape_string($conn, $_POST['time_slot']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Check if already booked same date & time
    $check = mysqli_query($conn,
        "SELECT * FROM bookings WHERE item_id='$item_id' AND item_type='$item_type' AND date='$date' AND time_slot='$time_slot'"
    );

    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Already booked on that date & time!'); window.history.back();</script>";
        exit();
    }

    // Insert booking
    $insert = mysqli_query($conn,
        "INSERT INTO bookings (item_id, item_type, name, email, phone, total_person, price, date, time_slot, purpose, message)
         VALUES ('$item_id', '$item_type', '$name', '$email', '$phone', '$total_person', '$price', '$date', '$time_slot', '$purpose', '$message')"
    );

    if ($insert) {
        echo "<script>alert('Booking confirmed successfully!'); window.location='event.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error booking'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Event</title>
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
  width:420px;
  backdrop-filter:blur(10px);
}
h2 { color:#f9d342; margin-bottom:20px; text-align:center; }
label { color:#f9d342; font-size:14px; }
input, textarea, select {
  width:100%;
  padding:10px;
  margin-top:5px;
  margin-bottom:15px;
  border-radius:8px;
  background:rgba(255,255,255,0.2);
  color:white;
  border:none;
}
.slot{
  color: #000;
}
button {
  width:100%;
  padding:12px;
  border:none;
  border-radius:20px;
  background:#f9d342;
  font-weight:bold;
}
button:hover { background:white; }
a.back-btn {
  color:#f9d342; display:block; margin-top:10px; text-align:center;
}
</style>
</head>
<body>

<section class="booking">
  <div class="booking-container">
    <h2>Book: <?= $event['name'] ?> (<?= ucfirst($item_type) ?>)</h2>

    <form method="POST">

      <label>Full Name</label>
      <input type="text" name="name" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Phone</label>
      <input type="text" name="phone" required>

      <label>Total Persons</label>
      <input type="number" name="total_person" required>

      <label>Price</label>
      <input type="number" name="price"
        value="<?= $item_type=='venue' ? $event['price'] : $event['price_per_plate'] ?>" required>

      <label>Date</label>
      <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>

      <label>Select Time</label>
      <select name="time_slot"  required>
        <option value=""class="slot">-- Select Time --</option>
        <option value="Morning (8 AM - 12 PM)" class="slot">Morning (8 AM - 12 PM)</option>
        <option value="Afternoon (12 PM - 4 PM)" class="slot">Afternoon (12 PM - 4 PM)</option>
        <option value="Evening (4 PM - 8 PM)" class="slot">Evening (4 PM - 8 PM)</option>
        <option value="Night (8 PM - 11 PM)" class="slot">Night (8 PM - 11 PM)</option>
      </select>

      <label>Purpose of Event</label>
      <select name="purpose"  required>
        <option value="" class="slot">-- Select Event --</option>
        <option value="Marriage" class="slot">Marriage</option>
        <option value="Birthday" class="slot">Birthday</option>
        <option value="Anniversary" class="slot">Anniversary</option>
        <option value="Corporate Meeting" class="slot">Corporate Meeting</option>
        <option value="Party" class="slot">Party</option>
        <option value="Other" class="slot">Other</option>
      </select>

      <label>Message (Optional)</label>
      <textarea name="message"></textarea>

      <button name="submit">Confirm Booking</button>
    </form>

    <a href="event.php" class="back-btn">← Back</a>
  </div>
</section>

</body>
</html>
