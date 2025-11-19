<?php
include('../connection.php');
session_start();

$message = "";

/* ===============================
   INSERT CATERING
================================ */
if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $menu = mysqli_real_escape_string($conn, $_POST['menu']);
    $price = floatval($_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // IMAGE UPLOAD
    $imageName = "";
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . $_FILES['image']['name'];
        $uploadPath = "../user/uploads/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
    }

    $query = "INSERT INTO catering (name, menu, price_per_plate, description, image)
              VALUES ('$name', '$menu', '$price', '$description', '$imageName')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Catering added successfully!'); window.location='events.php';</script>";
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
<title>Add Catering</title>
<link rel="stylesheet" href="admin_main.css">
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="admin-content">

<div class="form-box">
    <h2>Add Catering</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Catering Name" required>
        <textarea name="menu" placeholder="Menu Items" rows="3" required></textarea>
        <input type="number" step="0.01" name="price" placeholder="Price Per Plate (Rs.)" required>
        <textarea name="description" placeholder="Description" rows="4"></textarea>

        <label>Upload Image:</label>
        <input type="file" name="image">

        <button type="submit" name="submit">Add Catering</button>
    </form>

    <a href="events.php" class="back-btn">← Back</a>
</div>

</div>
</body>
</html>
