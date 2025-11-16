<?php
include('../connection.php');
session_start();

/* ===============================
   DELETE BOOKING
================================ */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $del = mysqli_query($conn, "DELETE FROM bookings WHERE id='$id'");

    if (!$del) {
        die("Delete Error: " . mysqli_error($conn));
    }

    echo "<script>alert('Booking deleted successfully!'); window.location='bookings.php';</script>";
    exit();
}

/* ============================================
   AUTO-DETECT EVENT TITLE COLUMN FROM EVENTS
=============================================== */

$possible_title_columns = ['event_title', 'title', 'name', 'event_name'];
$event_title_column = null;

$colRes = mysqli_query($conn, "SHOW COLUMNS FROM events");

if ($colRes) {
    while ($col = mysqli_fetch_assoc($colRes)) {
        if (in_array(strtolower($col['Field']), $possible_title_columns)) {
            $event_title_column = $col['Field']; // use exact DB name
            break;
        }
    }
}

/* If no title-like column exists */
if ($event_title_column) {
    $eventSelect = "e.`$event_title_column` AS event_name";
} else {
    $eventSelect = "'Unknown Event' AS event_name";
}

/* ===============================
   FETCH BOOKINGS (SAFE QUERY)
================================ */
$query = "
SELECT 
    b.id,
    b.event_id,
    b.event_type,
    b.name,
    b.email,
    b.phone,
    b.total_person,
    b.price,
    b.date,
    b.message,
    b.created_at,
    $eventSelect
FROM bookings b
LEFT JOIN events e ON b.event_id = e.id
ORDER BY b.id DESC
";

$result = mysqli_query($conn, $query);

// If query failed — show error box
if (!$result) {
    die("<div style='padding:20px;background:#ffdddd;border-left:5px solid red;margin-left:270px;margin-top:30px;'>
        <b>SQL Error:</b><br>" . mysqli_error($conn) . "<br><br>
        <b>Query:</b><br>$query
    </div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Bookings</title>

<link rel="stylesheet" href="admin_main.css">

<style>

h2 {
  font-size: 26px;
  margin-bottom: 20px;
  color: #333;
}

/* ------------------------------ Table ------------------------------ */
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

th, td {
  padding: 14px;
  text-align: center;
  border-bottom: 1px solid #eee;
}

th {
  background: #f9d342;
  color: #000;
  font-weight: bold;
}

.delete-btn {
  background: #dc3545;
  padding: 6px 12px;
  border-radius: 6px;
  color: #fff;
  text-decoration: none;
  font-size: 14px;
}

.delete-btn:hover {
  background: #b52a37;
}

/* Empty box */
.empty {
  margin-left: 270px;
  padding: 20px;
  background: white;
  text-align: center;
  margin-top: 40px;
  border-radius: 10px;
  font-size: 18px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="admin-navbar">
    <h2>Admin Dashboard</h2>
    <ul>
        <li><a href="users.php">Manage Users</a></li>
        <li><a href="events.php">Manage Events</a></li>
        <li><a href="bookings.php">View Bookings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="admin-content">
<h2>Manage Bookings</h2>

<?php if (mysqli_num_rows($result) === 0): ?>
    <div class="empty">No bookings found.</div>

<?php else: ?>
<table>
    <tr>
        <th>ID</th>
        <th>Event Name</th>
        <th>Event Type</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Total Person</th>
        <th>Price</th>
        <th>Date</th>
        <th>Message</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['event_name']) ?></td>
        <td><?= htmlspecialchars($row['event_type'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['total_person']) ?></td>
        <td><?= $row['price'] !== null ? "Rs. " . number_format($row['price'], 2) : "Rs. 0.00" ?></td>
        <td><?= htmlspecialchars($row['date']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>

        <td>
            <a class="delete-btn" 
               onclick="return confirm('Are you sure you want to delete this booking?')"
               href="bookings.php?delete=<?= $row['id'] ?>">
               Delete
            </a>
        </td>
    </tr>
    <?php endwhile; ?>

</table>
<?php endif; ?>

</div>

</body>
</html>
