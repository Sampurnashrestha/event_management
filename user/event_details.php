<?php
include "../connection.php";

$type = $_GET['type'];
$id = $_GET['id'];

if ($type === "venue") {
    $query = "SELECT * FROM venue WHERE id = $id";
} else {
    $query = "SELECT * FROM catering WHERE id = $id";
}

$data = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($data);

// Image fix
$img = "../user/uploads/" . $row['image'];
if (!file_exists($img) || empty($row['image'])) {
    $img = "../user/uploads/default.png";
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?= $row['name'] ?> - Details</title>

<style>
body {
    background: #000;
    color: white;
    font-family: Arial;
}
.container {
    max-width: 900px;
    margin: 50px auto;
    background: rgba(255,255,255,0.08);
    padding: 20px;
    border-radius: 15px;
}
.container img {
    width: 100%;
    height: 350px;
    border-radius: 15px;
    object-fit: cover;
    margin-bottom: 20px;
}

.book-btn {
    width: 100%;
    padding: 12px;
    background: #f9d342;
    border-radius: 8px;
    margin-top: 15px;
    cursor: pointer;
    font-weight: bold;
}
.book-btn:hover { background: white; color: black; }
</style>

</head>

<body>

<div class="container">
    <img src="<?= $img ?>">

    <h2><?= $row['name'] ?></h2>

    <p><strong>Type:</strong> <?= ucfirst($type) ?></p>

    <p><strong>Description:</strong><br><?= $row['description'] ?></p>

    <p><strong>Details:</strong><br>
    <?= ($type == "venue") ? $row['location'] : $row['menu'] ?></p>

    <?php if ($type == "venue") { ?>
        <p><strong>Capacity:</strong> <?= $row['capacity'] ?></p>
    <?php } ?>

    <p><strong>Price:</strong> 
        Rs. <?= $type == "venue" ? $row['price'] : $row['price_per_plate'] ?>
    </p>

    <button class="book-btn"
        onclick="location.href='book_event.php?type=<?= $type ?>&id=<?= $id ?>'">
        Book Now
    </button>

</div>

</body>
</html>
