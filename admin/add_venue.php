<?php
include('../connection.php');
session_start();

$message = "";

/* ===============================
   INSERT VENUE
================================ */
if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $capacity = intval($_POST['capacity']);
    $price = floatval($_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // IMAGE UPLOAD
    $imageName = "";
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . $_FILES['image']['name'];
        $uploadPath = "../user/uploads/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
    }

    $query = "INSERT INTO venue (name, location, capacity, price, description, image)
              VALUES ('$name', '$location', '$capacity', '$price', '$description', '$imageName')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Venue added successfully!'); window.location='events.php';</script>";
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Venue</title>
<link rel="stylesheet" href="./admin_main.css">

<style>

/* ============================
   CONTENT AREA
============================ */
.admin-content {
    margin-left: 230px;
    padding: 30px;
    width: calc(100% - 230px);
}

/* ============================
   FORM BOX
============================ */
.form-box {
    width: 500px;
    background: #fff;
    padding: 25px;
    margin: auto;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

.form-box h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-box input,
.form-box textarea,
.form-box select {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 15px;
}

.form-box button {
    width: 100%;
    padding: 12px;
    background: #222;
    color: white;
    border: none;
    font-size: 17px;
    border-radius: 6px;
    cursor: pointer;
}

.form-box button:hover {
    background: #444;
}

.back-btn {
    display: inline-block;
    margin-top: 10px;
    text-decoration: none;
    color: #222;
    font-size: 15px;
}

.back-btn:hover {
    text-decoration: underline;
}
</style>

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

<div class="form-box">
    <h2>Add Venue</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Venue Name" required>
        <input type="text" name="location" placeholder="Location" required>
        <input type="number" name="capacity" placeholder="Capacity" required>
        <input type="number" step="0.01" name="price" placeholder="Price (Rs.)" required>
        <textarea name="description" placeholder="Description" rows="4"></textarea>

        <label>Upload Image:</label>
        <input type="file" name="image">

        <button type="submit" name="submit">Add Venue</button>
    </form>

    <a href="events.php" class="back-btn">← Back</a>
</div>

</div>
</body>
</html>
