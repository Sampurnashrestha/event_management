<?php
include('../connection.php');
session_start();

/* ===============================
   DELETE VENUE
================================ */
if (isset($_GET['delete_venue'])) {
    $id = intval($_GET['delete_venue']);
    mysqli_query($conn, "DELETE FROM venue WHERE id = $id");
    echo "<script>alert('Venue deleted successfully!'); window.location='events.php';</script>";
    exit();
}

/* ===============================
   DELETE CATERING
================================ */
if (isset($_GET['delete_catering'])) {
    $id = intval($_GET['delete_catering']);
    mysqli_query($conn, "DELETE FROM catering WHERE id = $id");
    echo "<script>alert('Catering deleted successfully!'); window.location='events.php';</script>";
    exit();
}

/* ===============================
   FETCH VENUE
================================ */
$venueData = mysqli_query($conn, "SELECT * FROM venue ORDER BY id DESC");

/* ===============================
   FETCH CATERING
================================ */
$cateringData = mysqli_query($conn, "SELECT * FROM catering ORDER BY id DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Events</title>

<link rel="stylesheet" href="admin_main.css">

<style>
/* -------- Page Layout -------- */
.admin-content {
    margin-left: 270px;
    padding: 25px;
}

/* -------- Section Wrapper -------- */
.section-box {
    margin-bottom: 60px;
    padding: 25px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.10);
}

/* Title */
.section-box h2 {
    margin: 0 0 15px 0;
    font-size: 28px;
    font-weight: 700;
    color: #222;
}

/* Add Button */
.add-btn {
    display: inline-block;
    padding: 10px 18px;
    background: #0275ff;
    color: white;
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 18px;
}

.add-btn:hover {
    background: #005fcc;
}

/* Table */
.table-box {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 3px 10px rgba(0,0,0,0.10);
}

.table-box th {
    background: #f9d342;
    padding: 12px;
    text-align: center;
    font-weight: bold;
    color: #000;
}

.table-box td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

.table-box tr:hover {
    background: #f7f7f7;
}

/* Action buttons */
.edit-btn, .delete-btn {
    padding: 6px 12px;
    font-size: 14px;
    text-decoration: none;
    border-radius: 6px;
    color: white;
}

.edit-btn {
    background: #198754;
}

.edit-btn:hover {
    background: #157347;
}

.delete-btn {
    background: #dc3545;
}

.delete-btn:hover {
    background: #b02a37;
}
</style>

</head>
<body>

<!-- Sidebar -->
<div class="admin-navbar">
    <h2>Admin Dashboard</h2>
    <ul>
        <li><a href="users.php">Manage Users</a></li>
        <li><a href="events.php" class="active">Manage Events</a></li>
        <li><a href="bookings.php">View Bookings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>


<!-- MAIN CONTENT -->
<div class="admin-content">


<!-- ============================
     VENUE SECTION
============================ -->
<div class="section-box">
    <h2>Manage Venue</h2>

    <a href="add_venue.php" class="add-btn">+ Add Venue</a>

    <table class="table-box">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Capacity</th>
            <th>Price (Rs.)</th>
            <th>Action</th>
        </tr>

        <?php while ($v = mysqli_fetch_assoc($venueData)): ?>
        <tr>
            <td><?= $v['id'] ?></td>
            <td><?= htmlspecialchars($v['name']) ?></td>
            <td><?= htmlspecialchars($v['capacity']) ?></td>
            <td><?= number_format($v['price']) ?></td>

            <td>
                <a href="edit_venue.php?id=<?= $v['id'] ?>" class="edit-btn">Edit</a>
                <a href="events.php?delete_venue=<?= $v['id'] ?>" class="delete-btn"
                   onclick="return confirm('Delete this venue?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>
</div>



<!-- ============================
     CATERING SECTION
============================ -->
<div class="section-box">
    <h2>Manage Catering</h2>

    <a href="add_catering.php" class="add-btn">+ Add Catering</a>

    <table class="table-box">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price Per Plate (Rs.)</th>
            <th>Action</th>
        </tr>

        <?php while ($c = mysqli_fetch_assoc($cateringData)): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= number_format($c['price_per_plate']) ?></td>

            <td>
                <a href="edit_catering.php?id=<?= $c['id'] ?>" class="edit-btn">Edit</a>
                <a href="events.php?delete_catering=<?= $c['id'] ?>" class="delete-btn"
                   onclick="return confirm('Delete this catering item?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>
</div>

</div>

</body>
</html>
