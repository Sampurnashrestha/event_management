<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hamro Event</title>
  <link rel="stylesheet" href="nav.css" />
  <link rel="stylesheet" href="user_main.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
/>
</head>
<body>


  <nav class="navbar">
    <div class="logo">
      <a href="index.html">Hamro<span> Event</span></a>
    </div>

    <ul class="nav-links">
      <li><a href="index.html" class="active">Home</a></li>
      <li><a href="events.html">Events</a></li>
      <li><a href="index.php#about">About</a></li>
      <li><a href="index.php#contact">Contact</a></li>
    </ul>


    <div class="account" tabindex="0">
      <a href="#" class="account-icon"><i class="fa-solid fa-user-circle"></i></a>
      <ul class="account-dropdown">
        <li><a href="profile.html">Profile</a></li>
        <li><a href="../user/register/logout.php">Logout</a></li>
      </ul>
    </div>
  </nav>


  <section class="hero">
    <h1>Welcome to Hamro Event</h1>
    <p>Plan and manage your events easily.</p>
  </section>


<section class="about" id="about">
  <div class="container">
    <div class="about-content">
      <div class="about-image">
        <img src="../user/about.jpg" alt="Event">
      </div>


      <div class="about-text">
        <h2>About EventEsa</h2>
        <p>
          EventEsa is your all-in-one platform to plan and manage events effortlessly. 
          From weddings to corporate meetings, we provide the perfect halls, packages, 
          and tools to make your events memorable and stress-free.
        </p>
      </div>
    </div>

    
   
</section>
<section class="event-list-section">
<h3>event</h3>
  <div class="event-container">
    <a href="venues.html" class="event-item">
      <i class="fa-solid fa-building-columns"></i>
      <p>Venues</p>
    </a>

    <a href="decorations.html" class="event-item">
      <i class="fa-solid fa-fan"></i>
      <p>Decorations</p>
    </a>

    <a href="services.html" class="event-item">
      <i class="fa-solid fa-pen-nib"></i>
      <p>Services</p>
    </a>

    <a href="catering.html" class="event-item">
      <i class="fa-solid fa-utensils"></i>
      <p>Catering</p>
    </a>

    <a href="makeup.html" class="event-item">
      <i class="fa-solid fa-paintbrush"></i>
      <p>Makeup Artist</p>
    </a>

    <a href="photography.html" class="event-item">
      <i class="fa-solid fa-camera"></i>
      <p>Photography</p>
    </a>
  </div>
</section>


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
      <form>
        <input type="text" placeholder="Name" required>
        <input type="email" placeholder="Email" required>
        <textarea placeholder="Message" rows="5" required></textarea>
        <button type="submit">Send</button>
      </form>
    </div>
  </div>
</section>


</body>
</html>
