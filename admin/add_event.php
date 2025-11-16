<?php
include('../connection.php');
session_start();



if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $theme = $_POST['theme'];
    $style = $_POST['style'];
    $package = $_POST['package'];
    $price = $_POST['price'];
    
    // Image upload
    $image = 'default.png';
    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $image = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../user/uploads/'.$image);
    }

    mysqli_query($conn, "INSERT INTO events (name, category, location, description, theme, style, package, price, image) VALUES ('$name','$category','$location','$description','$theme','$style','$package','$price','$image')");
    echo "<script>alert('Event added successfully!'); window.location='events.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Event</title>
<link rel="stylesheet" href="admin_main.css">
<style>
.container {
    margin-left: 220px;
    padding: 40px;
    min-height: 100vh;
}
form {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    max-width: 700px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
form input, form select, form textarea {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #ddd;
}
form button {
    background: #007bff;
    color: #fff;
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
}
form button:hover { opacity: 0.85; }
</style>
</head>
<body>
<div class="container">
    <h2>Add New Event</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Event Name" required>
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="venues">Venues</option>
            <option value="decorations">Decorations</option>
            <option value="services">Services</option>
            <option value="catering">Catering</option>
            <option value="makeup">Makeup</option>
            <option value="photography">Photography</option>
        </select>
        <input type="text" name="location" placeholder="Location">
        <textarea name="description" placeholder="Description"></textarea>
        <input type="text" name="theme" placeholder="Theme">
        <input type="text" name="style" placeholder="Style">
        <input type="text" name="package" placeholder="Package">
        <input type="number" step="0.01" name="price" placeholder="Price">
        <input type="file" name="image">
        <button type="submit" name="submit">Add Event</button>
    </form>
</div>
</body>
</html>
