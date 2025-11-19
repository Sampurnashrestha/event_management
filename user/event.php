<?php
session_start();
include('../connection.php'); 

if (!isset($_SESSION['user'])) {
    header("Location: ../user/register/login.php");
    exit();
}

$user_email = $_SESSION['user'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$user_email'");
$user = mysqli_fetch_assoc($query);

// Get filters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Base queries
$venueQuery = "
    SELECT 
        id, 
        name, 
        location AS details, 
        price, 
        capacity, 
        description, 
        image, 
        'venue' AS type 
    FROM venue 
    WHERE 1
";

$cateringQuery = "
    SELECT 
        id, 
        name,
        menu AS details, 
        price_per_plate AS price, 
        0 AS capacity,
        description,
        image,
        'catering' AS type
    FROM catering
    WHERE 1
";

// Search filter
if (!empty($search)) {
    $venueQuery .= " AND (name LIKE '%$search%' OR location LIKE '%$search%' OR description LIKE '%$search%')";
    $cateringQuery .= " AND (name LIKE '%$search%' OR menu LIKE '%$search%' OR description LIKE '%$search%')";
}

// Category filter
if ($category === 'venue') {
    $finalQuery = $venueQuery;
} elseif ($category === 'catering') {
    $finalQuery = $cateringQuery;
} else {
    $finalQuery = "$venueQuery UNION $cateringQuery";
}

// Sorting
if ($sort === 'low-high') {
    $finalQuery .= " ORDER BY price ASC";
} elseif ($sort === 'high-low') {
    $finalQuery .= " ORDER BY price DESC";
}

$data = mysqli_query($conn, $finalQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Venue & Catering</title>

<link rel="stylesheet" href="nav.css" />
<link rel="stylesheet" href="user_main.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>

.event_section {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 0;
    color: white;
    text-align: center;
}

/* ===== FILTER BAR ===== */
.filter-box {
    width: 92%;
    margin: 20px auto;
    padding: 20px;
    background: rgba(255,255,255,0.08);
    border-radius: 18px;
    backdrop-filter: blur(18px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    box-shadow: 0 6px 25px rgba(0,0,0,0.4);
}

/* ===== CATEGORY BUTTONS ===== */
.category-btns {
    display: flex;
    gap: 10px;
}
.category-btns button {
    padding: 10px 25px;
    border: none;
    background: rgba(255,255,255,0.12);
    color: white;
    border-radius: 50px;
    cursor: pointer;
    font-weight: 600;
    font-size: 15px;
    transition: 0.25s ease;
}
.category-btns button:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}
.category-btns .active {
    background: #f9d342;
    color: black;
    box-shadow: 0 4px 14px rgba(249,211,66,0.5);
}

/* ===== SEARCH BOX ===== */
.search-box {
    position: relative;
}
.search-box input {
    padding: 12px 45px 12px 18px;
    width: 260px;
    border-radius: 50px;
    border: 2px solid transparent;
    background: rgba(255,255,255,0.15);
    color: white;
    outline: none;
    transition: 0.3s;
}
.search-box input:focus {
    border-color: #f9d342;
    background: rgba(255,255,255,0.25);
}
.search-box i {
    position: absolute;
    right: 15px;
    top: 12px;
    font-size: 18px;
    color: #f9d342;
}

/* ===== SORT BOX ===== */
.sort-box select {
    padding: 12px 18px;
    border-radius: 50px;
    border: none;
    outline: none;
    background: rgba(255,255,255,0.15);
    color: white;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}
.sort-box select:hover {
    background: rgba(255,255,255,0.25);
}
.sort-box select:focus {
    background: rgba(255,255,255,0.3);
    border: 2px solid #f9d342;
}

/* ===== EVENT GRID ===== */
.event-list {
    width: 92%;
    margin: 25px auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
    gap: 25px;
}

/* ===== EVENT CARD ===== */
.event-card {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    overflow: hidden;
    transition: 0.3s ease;
    cursor: pointer;
    border: 1px solid rgba(255,255,255,0.15);
}
.event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
}
.event-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
}
.info {
    padding: 18px;
}
.info h4 {
    color: #f9d342;
    font-size: 20px;
    margin-bottom: 8px;
}
.info p {
    font-size: 14px;
    margin-bottom: 6px;
}
.book-btn {
    width: 100%;
    margin-top: 10px;
    padding: 12px 0;
    border: none;
    border-radius: 10px;
    background: linear-gradient(45deg, #f9d342, #ffe177);
    color: black;
    font-weight: bold;
    cursor: pointer;
    transition: 0.25s;
}
.book-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(249,211,66,0.6);
}

/* ===== POPUP ===== */
.popup-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 999;
}
.popup-box {
    width: 60%;
    max-width: 700px;
    background: rgba(255,255,255,0.08);
    border-radius: 20px;
    padding: 20px;
    backdrop-filter: blur(15px);
    color: white;
    border: 1px solid rgba(255,255,255,0.2);
    animation: pop .3s ease;
    position: relative;
    text-align: left;
}
.popup-box img {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 15px;
    margin-bottom: 15px;
    transition: transform 0.3s ease;
}
.popup-box img:hover {
    transform: scale(1.05);
}
.close-btn {
    position: absolute;
    right: 20px;
    top: 8px;
    font-size: 30px;
    cursor: pointer;
    color: white;
    transition: 0.3s;
}
.close-btn:hover {
    color: red;
}
@keyframes pop {
    from { opacity: 0; transform: scale(0.7); }
    to { opacity: 1; transform: scale(1); }
}

/* responsive */
@media (max-width: 768px) {
    .filter-box {
        flex-direction: column;
        gap: 18px;
        text-align: center;
    }
    .search-box input { width: 100%; }
    .popup-box { width: 90%; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo">
    <a href="index.php">Hamro<span> Event</span></a>
  </div>
  <ul class="nav-links">
    <li><a href="index.php" >Home</a></li>
    <li><a href="event.php" class="active">Events</a></li>
    <li><a href="index.php#about">About</a></li>
    <li><a href="index.php#contact">Contact</a></li>
  </ul>
  <div class="account" tabindex="0">
    <a href="#" class="account-icon"><i class="fa-solid fa-user-circle"></i></a>
    <ul class="account-dropdown">
      <li><a href="account.php">Profile</a></li>
      <li><a href="../user/register/logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<div class="event_section">

<form method="GET">
<div class="filter-box">
  <div class="category-btns">
      <button name="category" value="all" class="<?php echo ($category=='all')?'active':''; ?>">All</button>
      <button name="category" value="venue" class="<?php echo ($category=='venue')?'active':''; ?>">Venue</button>
      <button name="category" value="catering" class="<?php echo ($category=='catering')?'active':''; ?>">Catering</button>
  </div>
  <div class="search-box">
      <input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">
  </div>
  <div class="sort-box">
      <select name="sort" onchange="this.form.submit()">
          <option value="default" <?php if($sort=='default') echo 'selected'; ?>>Default</option>
          <option value="low-high" <?php if($sort=='low-high') echo 'selected'; ?>>Low → High</option>
          <option value="high-low" <?php if($sort=='high-low') echo 'selected'; ?>>High → Low</option>
      </select>
  </div>
</div>
</form>

<div class="event-list">
<?php
while ($row = mysqli_fetch_assoc($data)) {

    $img = "../user/uploads/" . $row['image'];
    if (!file_exists($img) || empty($row['image'])) {
        $img = "../user/uploads/default.png";
    }

    echo "
    <div class='event-card'>
        <img src='$img' onclick=\"openPopup('".addslashes($img)."', '".addslashes($row['name'])."', '".addslashes($row['details'])."', '".addslashes($row['price'])."', '".addslashes($row['description'])."', '".addslashes($row['type'])."')\">
        <div class='info'>
            <h4>".htmlspecialchars($row['name'])."</h4>
            <p>Type: ".htmlspecialchars($row['type'])."</p>
            <p>Details: ".htmlspecialchars($row['details'])."</p>
            <p style='color:#f9d342;font-weight:bold;'>Rs. ".htmlspecialchars($row['price'])."</p>
            <button class='book-btn' onclick=\"event.stopPropagation(); location.href='book_event.php?type={$row['type']}&id={$row['id']}'\">Book Now</button>
        </div>
    </div>";
}
?>
</div>

<!-- POPUP -->
<div class="popup-overlay" id="popup">
  <div class="popup-box">
      <span class="close-btn" onclick="closePopup()">×</span>
      <img id="popup-image">
      <h2 id="popup-name"></h2>
      <p id="popup-type"></p>
      <p id="popup-details"></p>
      <h3 style="color:#f9d342;">Price: Rs. <span id="popup-price"></span></h3>
      <p id="popup-description"></p>
  </div>
</div>

<script>
function openPopup(img, name, details, price, description, type) {
    document.getElementById("popup-image").src = img;
    document.getElementById("popup-name").innerText = name;
    document.getElementById("popup-type").innerText = "Type: " + type;
    document.getElementById("popup-details").innerText = "Details: " + details;
    document.getElementById("popup-price").innerText = price;
    document.getElementById("popup-description").innerText = description;
    document.getElementById("popup").style.display = "flex";
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
}

window.onclick = function(e) {
    let popup = document.getElementById("popup");
    if (e.target === popup) {
        popup.style.display = "none";
    }
}
</script>

</div>
</body>
</html>
