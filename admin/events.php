<?php
include('../connection.php');
session_start();



// Delete Event
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM events WHERE id='$id'");
    echo "<script>alert('Event deleted successfully!'); window.location='events.php';</script>";
    exit;
}

// Fetch all events
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Events - Admin Panel</title>
<link rel="stylesheet" href="admin_main.css">
<style>
/* ------------------------------ Container ------------------------------ */
.container {
    margin-left: 320px; /* matches navbar width */
    padding: 40px;
    min-height: 100vh;
    background: #f4f4f9;
}

/* Header: title + add button inline */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.header h2 {
    margin: 0;
    font-size: 26px;
    color: #333;
}
.add-btn {
    background: #007bff;
    color: #fff;
    padding: 8px 18px;
    border-radius: 6px;
    text-decoration: none;
    transition: 0.3s;
}
.add-btn:hover { opacity: 0.85; }

/* ------------------------------ Table Styling ------------------------------ */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
th, td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}
th {
    background-color: #007bff;
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
}

/* Buttons */
.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    transition: 0.3s;
}
.edit { background: #28a745; color: #fff; }
.delete { background: #dc3545; color: #fff; }
.btn:hover { opacity: 0.85; }

/* ------------------------------ Responsive Table ------------------------------ */
@media (max-width: 768px) {
    table, thead, tbody, th, td, tr { display: block; }
    th { display: none; }
    td {
        position: relative;
        padding-left: 50%;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    td::before {
        position: absolute;
        left: 15px;
        top: 12px;
        font-weight: bold;
        color: #007bff;
        content: attr(data-label);
    }
    .header { flex-direction: column; align-items: flex-start; }
    .add-btn { margin-top: 10px; }
}

/* ------------------------------ Navbar Styling ------------------------------ */
.admin-navbar {
    width: 320px; /* matches margin-left of container */
    background: #1f1f1f;
    color: #fff;
    position: fixed;
    height: 100vh;
    padding: 30px 20px;
    box-sizing: border-box;
}
.admin-navbar h2 {
    font-size: 24px;
    color: #f9d342;
    margin-bottom: 25px;
}
.admin-navbar ul {
    list-style: none;
    padding: 0;
}
.admin-navbar ul li {
    margin-bottom: 15px;
}
.admin-navbar ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    display: block;
    padding: 8px 12px;
    border-radius: 6px;
    transition: 0.3s;
}
.admin-navbar ul li a:hover {
    background: #f9d342;
    color: #000;
    font-weight: bold;
}
</style>
</head>
<body>

<!-- Navbar -->
<div class="admin-navbar">
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="users.php">Manage Users</a></li>
      <li><a href="manage_events.php">Manage Events</a></li>
      <li><a href="bookings.php">View Bookings</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Container -->
<div class="container">
    <div class="header">
        <h2>Manage Events</h2>
        <a href="add_event.php" class="add-btn">+ Add Event</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Location</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($events)) { ?>
            <tr>
                <td data-label="ID"><?= $row['id']; ?></td>
                <td data-label="Name"><?= htmlspecialchars($row['name']); ?></td>
                <td data-label="Category"><?= $row['category']; ?></td>
                <td data-label="Location"><?= htmlspecialchars($row['location']); ?></td>
                <td data-label="Price">$<?= $row['price']; ?></td>
                <td data-label="Status"><?= ucfirst($row['availability_status']); ?></td>
                <td data-label="Actions">
                    <a href="edit_event.php?id=<?= $row['id']; ?>" class="btn edit">Edit</a>
                    <a href="events.php?delete=<?= $row['id']; ?>" class="btn delete" onclick="return confirm('Delete this event?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
