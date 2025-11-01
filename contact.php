<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>READIFY Bookstore - Contact</title>
  <link rel="stylesheet" href="./css/common.css" />
  <link rel="stylesheet" href="./css/contact.css" />
</head>

<body>
  <!-- Header -->
  <header class="header">
    <div class="container-inner">
      <a href="index.php" class="logo-link">
        <div class="logo">
          <h1>READIFY</h1>
          <img src="./assets/title.png" alt="BookShop Logo" class="logo-resize" />
        </div>
      </a>
      <nav class="nav-link">
        <ul>
          <li><a href="./index.php">Home</a></li>
          <li><a href="./product.php">Books</a></li>
          <li><a href="./aboutus.php">About Us</a></li>
          <li><a href="./contact.php">Contact Us</a></li>
          <li><a href="./cart.php">Cart</a></li>

          <!-- Handle user login session -->
          <?php if (isset($_SESSION['user'])): ?>
            <li>Welcome, <?= $_SESSION['user']['name']; ?></li>
            <li><a href="logout.php">Logout</a></li>
          <?php else: ?>
            <li><a href="signin.php">Sign In</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Body -->
  <section>
    <div class="contact-container">
      <div class="contact-info">
        <h2>Contact Us</h2><br>
        <p>Please contact us through our website, email, or phone. <br> You can also visit one of our 4 bookstores
          across Sri Lanka. <br> We are committed to providing the best customer service and <br>will do our best to
          assist you. <br><br><b>ONLINE WORKING HOURS </b><br><br> Monday to Friday - 9.00am - 5.30 pm <br>Saturday -
          9.00 am - 1.30 pm</p><br>

        <div class="info-box">
          <div class="icon">🏠</div>
          <div class="info-text">
            <strong>Address</strong><br>
            No:123 Matara Road,<br>
            Kamburupitiya.
          </div>
        </div>

        <div class="info-box">
          <div class="icon">📞</div>
          <div class="info-text">
            <strong>Phone</strong><br>
            077 123 4567
          </div>
        </div>

        <div class="info-box">
          <div class="icon">✉️</div>
          <div class="info-text">
            <strong>Email</strong><br>
            readify@gmail.com
          </div>
        </div>
      </div>

      <div class="form-container">
        <h2>Send Message</h2>
        <form class="from-collect" method="POST" action="./config/contact_get_info.php">
          <input type="text" name="name" id="name" placeholder="Full Name" required>
          <input type="email" name="email" id="email" placeholder="Email" required>
          <textarea name="message" rows="4" id="message" placeholder="Type your Message..." required></textarea>
          <input type="submit" name="send-button" class="send-button "></input>
        </form>
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
          <li><a href="/feedback.php">Customer Feedback</a></li>
          <li><a href="/offers.php">Offers</a></li>
          <li><a href="/payment.php">payment</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Legal</h4>
        <ul class="links">
          <li><a href="/policy.php">Privacy Policy</a></li>
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

  <script src="/js/main.js"></script>
</body>

</html>