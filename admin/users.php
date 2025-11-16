<?php
include('../connection.php');
session_start();


// Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE user_id='$id'");
    echo "<script>alert('User deleted successfully!'); window.location='user_management.php';</script>";
    exit;
}

// Fetch All Users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY user_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Management - Admin Panel</title>
<link rel="stylesheet" href="./admin_main.css">
<style>

/* ------------------------------ Container ------------------------------ */
.container {
    margin-left: 320px; /* adjust based on your navbar width */
    padding: 40px;
    min-height: 100vh;
    background: #f4f4f9; /* matches body background */
}

/* ------------------------------ Headings ------------------------------ */
h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 24px;
}

/* ------------------------------ Table Styling ------------------------------ */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
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

.edit {
    background: #28a745;
    color: #fff;
}

.delete {
    background: #dc3545;
    color: #fff;
}

.btn:hover {
    opacity: 0.85;
}

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


<div class="container">
    <h2>👥 User Management</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($users)) { ?>
            <tr>
                <td data-label="ID"><?= $row['user_id']; ?></td>
                <td data-label="Username"><?= htmlspecialchars($row['username']); ?></td>
                <td data-label="Email"><?= htmlspecialchars($row['email']); ?></td>
                <td data-label="Actions">
                    <a href="user_management.php?delete=<?= $row['user_id']; ?>" class="btn delete" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
