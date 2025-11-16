<?php
include "../connection.php";

// Handle search, category, and sort filters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Build query
if ($category === 'all') {
  $query = "SELECT * FROM events WHERE 1";
} else {
  $query = "SELECT * FROM events WHERE category='$category'";
}

// Add search filter
if (!empty($search)) {
  $query .= " AND (name LIKE '%$search%' OR location LIKE '%$search%' OR description LIKE '%$search%')";
}

// Add sorting option
if ($sort === 'low-high') {
  $query .= " ORDER BY price ASC";
} elseif ($sort === 'high-low') {
  $query .= " ORDER BY price DESC";
}

$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hamro Event | Events</title>
<link rel="stylesheet" href="./nav.css">
<link rel="stylesheet" href="./user_main.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>

/* Event Section */
/* --------------------------------------------------
   Fade-In + Slide Animation (Hero Style)
-------------------------------------------------- */
@keyframes fadeSlide {
  0% {
    opacity: 0;
    transform: translateY(25px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* --------------------------------------------------
   Event Section
-------------------------------------------------- */
.event-section {
  margin-top: 130px;        /* Push whole content down */
  padding-top: 130px;        /* Extra spacing from top */
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  background: rgba(0, 0, 0, 0.4);

  opacity: 0;
  animation: fadeSlide 1s forwards;
  animation-delay: 0.2s;
}

/* --------------------------------------------------
   Search Bar
-------------------------------------------------- */
.search-bar-container {
  display: flex;
  justify-content: center;
  margin-bottom: 30px;
  
  opacity: 0;
  animation: fadeSlide 1s forwards;
  animation-delay: 0.4s;
}

.search-bar {
  display: flex;
  color: #fff;
  width: 60%;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 35px;
  overflow: hidden;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
}


.search-bar input {
  flex: 1;
  padding: 14px 20px;
  border: none;
  background: transparent;
  color: #fff;
  font-size: 16px;
  outline: none;
}

.search-bar button {
  background: #f9d342;
  border: none;
  padding: 14px 22px;
  cursor: pointer;
  font-size: 18px;
  transition: 0.3s ease;
}

.search-bar button:hover {
  background: white;
  color: black;
}

/* --------------------------------------------------
   Filters
-------------------------------------------------- */
.filter-section {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  margin-bottom: 40px;
  flex-wrap: wrap;

  opacity: 0;
  animation: fadeSlide 1s forwards;
  animation-delay: 0.6s;
}

.category-section a {
  text-decoration: none;
  color: #fff;
  font-size: 15px;
  padding: 10px 18px;
  border-radius: 25px;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  transition: 0.3s ease;
}

.category-section a:hover,
.category-section a.active {
  background: #f9d342;
  color: #000;
}

.sort-select {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  padding: 10px 18px;
  color: #fff;
  border-radius: 25px;
  cursor: pointer;
  outline: none;
  transition: 0.3s ease;
}

.sort-select:hover {
  background: #f9d342;
  color: black;
}

/* --------------------------------------------------
   Event Cards Grid
-------------------------------------------------- */
.event-list {
  width: 100%;
  max-width: 1200px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;

  opacity: 0;
  animation: fadeSlide 1s forwards;
  animation-delay: 0.8s;
}

/* --------------------------------------------------
   Event Card
-------------------------------------------------- */
.event-card {
  background: rgba(255, 255, 255, 0.06);
  border-radius: 18px;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,0.15);
  backdrop-filter: blur(12px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.4);
  transition: 0.3s ease;

  opacity: 0;
  animation: fadeSlide 0.9s forwards;
  animation-delay: 1s;
}

.event-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.7);
}

.event-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

/* --------------------------------------------------
   Info Box
-------------------------------------------------- */
.info {
  padding: 15px;
  text-align: left;

  opacity: 0;
  animation: fadeSlide 1s forwards;
  animation-delay: 1.2s;
}

.info h4 {
  font-size: 20px;
  color: #f9d342;
  margin-bottom: 10px;
}

.info p {
  color: #ddd;
  font-size: 14px;
  margin: 6px 0;
}

.price {
  font-size: 16px;
  font-weight: 600;
  color: #f9d342;
}

/* --------------------------------------------------
   Book Button
-------------------------------------------------- */
.book-btn {
  margin-top: 10px;
  width: 100%;
  padding: 10px 18px;
  border-radius: 30px;
  border: none;
  background: #f9d342;
  color: #000;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s ease;
}

.book-btn:hover {
  background: #fff;
  transform: scale(1.06);
}

.book-btn:disabled {
  background: gray;
  cursor: not-allowed;
  color: #fff;
}

/* --------------------------------------------------
   Responsive
-------------------------------------------------- */
@media (max-width: 768px) {
  .search-bar {
    width: 85%;
  }
  .event-list {
    padding: 10px;
  }
}

</style>
</head>
<body>

<nav class="navbar">
  <div class="logo"><a href="index.php">Hamro<span> Event</span></a></div>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="event.php" class="active">Events</a></li>
    <li><a href="book_event.php">Booking</a></li>
    <li><a href="#about">About</a></li>
    <li><a href="#contact">Contact</a></li>
  </ul>
  <div class="account" tabindex="0">
    <a href="#" class="account-icon"><i class="fa-solid fa-user-circle"></i></a>
    <ul class="account-dropdown">
      <li><a href="account.php">Profile</a></li>
      <li><a href="../user/register/logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<section class="event-section">
  <!-- Search -->
  <div class="search-bar-container">
    <form class="search-bar" method="GET">
      <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
      <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>

  <!-- Filters -->
  <div class="filter-section">
    <div class="category-section">
      <?php
      $categories = ['all','venues','decorations','services','catering','makeup','photography'];
      foreach($categories as $cat){
        $active = ($category == $cat) ? 'active' : '';
        echo "<a href='?category=$cat&sort=$sort' class='$active'>" . ucfirst($cat) . "</a>";
      }
      ?>
    </div>

    <form method="GET" style="display:inline;">
      <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
      <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
      <select name="sort" class="sort-select" onchange="this.form.submit()">
        <option value="default" <?= $sort=='default'?'selected':'' ?>>Sort by</option>
        <option value="low-high" <?= $sort=='low-high'?'selected':'' ?>>Price: Low → High</option>
        <option value="high-low" <?= $sort=='high-low'?'selected':'' ?>>Price: High → Low</option>
      </select>
    </form>
  </div>

  <!-- Event Cards -->
  <div class="event-list">
  <?php
  if(mysqli_num_rows($data) > 0){
    while($row = mysqli_fetch_assoc($data)){
        $img_path = '../user/uploads/' . $row['image'];
        if(!file_exists($img_path) || empty($row['image'])) $img_path = '../user/uploads/default.png';
        $status = ucfirst($row['availability_status']);
        $disabled = ($row['availability_status']=='available')?'':'disabled';
        echo "<div class='event-card'>
                <img src='$img_path' alt='{$row['name']}'>
                <div class='info'>
                  <h4>{$row['name']}</h4>
                  <p>Category: ".ucfirst($row['category'])."</p>
                  <p>Location: {$row['location']}</p>
                  <p class='price'>Rs. {$row['price']}</p>
                  <p>Status: $status</p>
                  <button class='book-btn' $disabled onclick='location.href=\"book_event.php?event_id={$row['id']}\"'>Book Now</button>
                </div>
              </div>";
    }
  } else {
    echo "<p style='text-align:center;'>No results found.</p>";
  }
  ?>
  </div>
</section>

</body>
</html>
