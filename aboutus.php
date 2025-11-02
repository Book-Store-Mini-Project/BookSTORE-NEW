<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>READIFY Bookstore</title>
  <link rel="icon" type="image/png" href="/assets/img-title.png">
  <link rel="stylesheet" href="./css/aboutus.css">
  <link rel="stylesheet" href="./css/common.css">
  <style>
    #user-info{
      color: black;
      font-size: large;
      background-color: greenyellow;
      padding: 10px;
      cursor: pointer;
      border-radius: 50px;
    }
  </style>
</head>

<body>
  
<!--Header -->
  <header class="header">
    <div class="container-inner">
      <a href="#" class="logo-link">
        <div class="logo">
          <h1>READIFY</h1>
          <img src="./assets/title.png" alt="BookShop Logo" class="logo-resize" />
        </div>
      </a>
      <button class="hamburger" id="hamburger">&#9776;</button>
      <nav class="nav-link" id="nav">
        <button class="close-icon" id="close-icon">&times;</button>
        <ul>
          <li><a href="./index.php">Home</a></li>
          <li><a href="./product.php">Books</a></li>
          <li><a href="./aboutus.php">About Us</a></li>
          <li><a href="./contact.php">Contact Us</a></li>
          <li><a href="./cart.php">Cart</a></li>

          <!-- USER NAME (only if logged in) -->
          <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
            <li id="user-info">
              <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </li>
          <?php endif; ?>

          <!-- SIGN IN / SIGN OUT -->
          <li>
            <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
              <a href="./logout.php" id="auth-action">Sign Out</a>
            <?php else: ?>
              <a href="./loginPage.php" id="auth-action">Sign In</a>
            <?php endif; ?>
          </li>

        </ul>
      </nav>
    </div>
  </header>


  <div class="second-header">
    <h1 style="text-align: center;">WE BELIVE THAT BOOKS CAN CHANGE YOUR LIFE.</h1>
  </div>

  <section id="about" class="about">

    <div class="container">

      <h1>About Us</h1>

      <div class="content">

        <div class="text">
          <h2 style="margin-bottom:15px;">Welcome to Our Bookstore</h2>
          <p>At <strong>READIFY</strong>, we believe in the magic of books. Our store is a haven for book lovers,
            offering a carefully curated collection of classics, bestsellers, and hidden gems.</p>
          <p>We offer a cozy reading environment, personalized book recommendations, and special literary events to
            bring readers and writers together.</p>
          <a href="./contact.php" class="btn">Contact Us</a>
        </div>
      </div>
    </div>
  </section>

  <section id="mission" class="mission">
    <div class="container">
      <h2 style="margin-bottom:15px;">Our Mission & Vision</h2>
      <p>Our mission is to cultivate a culture of reading and learning by making books more accessible to everyone. We
        strive to support emerging authors, promote literacy, and create a vibrant community of book lovers.</p>
      <p>Our vision is to be more than just a bookstore; we aim to be a literary hub where creativity, knowledge, and
        passion for books thrive.</p>
    </div>
  </section>

  <section id="features" class="why-choose-us">
    <div class="container">
      <h2>Why Choose Us?</h2>
      <div class="features-grid">
        <div class="feature-box">
          <h3>📚 Wide Selection</h3>
          <p>From fiction to academic resources, we have it all.</p>
        </div>
        <div class="feature-box">
          <h3>🌍 Community & Events</h3>
          <p>Engaging book clubs, author meetups, and storytelling sessions.</p>
        </div>
        <div class="feature-box">
          <h3>🎁 Personalized Service</h3>
          <p>Our experts help you find your next favorite book.</p>
        </div>
        <div class="feature-box">
          <h3>📦 Fast Delivery</h3>
          <p>Get your books delivered to your doorstep hassle free.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="team" class="team">
    <div class="container">
      <h2 style="margin-bottom:15px;">Our Team</h2>
      <p>We are a passionate team of book lovers dedicated to providing the best experience for our customers. Our team
        is here to help you find the perfect book, answer your questions, and make your visit memorable.</p>
      <div>
        <h2 style="margin-top: 50px;">Meet Our Team</h2>
        <div class="team-grid">
          <div class="team-member">
            <img src="./assets/Team Members/John Doe.jpg" alt="Team Member 1">
            <h3>John Doe</h3>
            <p>Founder & CEO</p>
          </div>

          <div class="team-member">
            <img src="./assets/Team Members/Emily Johnson.jpg" alt="Team Member 3">
            <h3>Emily Johnson</h3>
            <p>Customer Service Manager</p>
          </div>

          <div class="team-member">
            <img src="./assets/Team Members/Jane Smith.jpg" alt="Team Member 2">
            <h3>Jane Smith</h3>
            <p>Head of Marketing</p>
          </div>
        </div>
      </div>
  </section>


  <!-- Footer -->
  <section class="footer">
    <div class="footer-row">
      <div class="footer-col">
        <h4>Useful Links</h4>
        <ul class="links">
          <li><a href="./index.php">Home</a></li>
          <li><a href="./aboutus.php">About Us</a></li>
          <li><a href="./contact.php">Contact Us</a></li>
          <li><a href="./cart.php">Cart</a></li>
          <li><a href="./ordered_items.php">Orders</a></li>

        </ul>
      </div>
      <div class="footer-col">
        <h4>Explore</h4>
        <ul class="links">
          <li><a href="./feedback.php">Customer Feedback</a></li>
          <li><a href="./offers.php">Offers</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Legal</h4>
        <ul class="links">
          <li><a href="./policy.php">Privacy Policy</a></li>
          <li><a href="./FAQ.php">FAQ</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Newsletter</h4>
        <p>
          Subscribe to our newsletter for a weekly dose of news, updates,
          helpful tips, and exclusive offers.
        </p>
        <form action="#">
          <input type="text" placeholder="Your email" required />
          <button type="submit">SUBSCRIBE</button>
        </form>

      </div>
    </div>
  </section>

<body>
<html>