<?php
session_start();
include('../connection.php'); // ✅ adjust path based on your folder

// ✅ If user is not logged in, redirect to login
if (!isset($_SESSION['user'])) {
    header("Location: ../user/register/login.php");
    exit();
}

// ✅ Fetch logged-in user info
$user_email = $_SESSION['user'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$user_email'");
$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hamro Event</title>

  <link rel="stylesheet" href="nav.css" />
  <link rel="stylesheet" href="user_main.css" />

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />
</head>
<body>

  <!-- ✅ Navigation Bar -->
  <nav class="navbar">
    <div class="logo">
      <a href="index.php">Hamro<span> Event</span></a>
    </div>

    <ul class="nav-links">
      <li><a href="index.php" >Home</a></li>
      <li><a href="events.php">Events</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>

    <!-- ✅ Account Dropdown -->
    <div class="account" tabindex="0">
      <a href="#" class="account-icon"><i class="fa-solid fa-user-circle"></i></a>
      <ul class="account-dropdown">
        <li><a href="account.php">Profile</a></li>
        <li><a href="../user/register/logout.php">Logout</a></li>
      </ul>
    </div>
  </nav>

  <!-- ✅ Hero Section -->
  <section class="hero">
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?> 👋</h1>
    <p>Plan and manage your events easily with Hamro Event.</p>
  </section>

  <!-- ✅ About Section -->
  <section class="about" id="about">
    <div class="container">
      <div class="about-content">
        <div class="about-image">
          <img src="../user/about.jpg" alt="Event">
        </div>

        <div class="about-text">
          <h2>About Hamro Event</h2>
          <p>
            Hamro Event is your all-in-one platform to plan and manage events effortlessly. 
            From weddings to corporate meetings, we provide the perfect venues, packages, 
            and services to make your events memorable and stress-free.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ✅ Event Categories Section -->
  <section class="event-list-section">
    <h3>Our Event Services</h3>
    <div class="event-container">
      <a href="venues.php" class="event-item">
        <i class="fa-solid fa-building-columns"></i>
        <p>Venues</p>
      </a>

      <a href="decorations.php" class="event-item">
        <i class="fa-solid fa-fan"></i>
        <p>Decorations</p>
      </a>

      <a href="services.php" class="event-item">
        <i class="fa-solid fa-pen-nib"></i>
        <p>Services</p>
      </a>

      <a href="catering.php" class="event-item">
        <i class="fa-solid fa-utensils"></i>
        <p>Catering</p>
      </a>

      <a href="makeup.php" class="event-item">
        <i class="fa-solid fa-paintbrush"></i>
        <p>Makeup Artist</p>
      </a>

      <a href="photography.php" class="event-item">
        <i class="fa-solid fa-camera"></i>
        <p>Photography</p>
      </a>
    </div>
  </section>

  <!-- ✅ Contact Section -->
  <section class="contact-section" id="contact">
    <div class="contact-container">
      <!-- Left Column: Info -->
      <div class="contact-info">
        <h3>Contact</h3>
        <div class="info-item">
          <i class="fa-solid fa-location-dot"></i>
          <p>Kathmandu, Nepal</p>
        </div>
        <div class="info-item">
          <i class="fa-solid fa-phone"></i>
          <p>TEL: 01-000000</p>
        </div>
        <div class="info-item">
          <i class="fa-solid fa-envelope"></i>
          <p>hamroevent@gmail.com</p>
        </div>
      </div>

      <!-- Right Column: Contact Form -->
      <div class="contact-form">
        <h3>Send a <span>Message</span></h3>
        <form method="POST" action="">
          <input type="text" name="name" placeholder="Name" required>
          <input type="email" name="email" placeholder="Email" required>
          <textarea name="message" placeholder="Message" rows="5" required></textarea>
          <button type="submit">Send</button>
        </form>
      </div>
    </div>
  </section>

</body>
</html>
