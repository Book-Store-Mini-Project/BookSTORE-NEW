<?php
require_once 'config/db_connection.php';
session_start();
// Handle feedback form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $message = mysqli_real_escape_string($conn, $_POST['message']);

  $sql = "INSERT INTO feedback (name, email, message) VALUES ('$name', '$email', '$message')";
  mysqli_query($conn, $sql);
}

// Fetch all feedbacks
$feedbacks = [];
$result = mysqli_query($conn, "SELECT * FROM feedback ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) {
  $feedbacks[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>READIFY Bookstore - Feedback</title>
  <link rel="icon" type="image/png" href="/assets/img-title.png">
  <link rel="stylesheet" href="css/common.css" />
  <link rel="stylesheet" href="./css/feedback.css" />
  <style>
    #user-info {
      color: black;
      font-size: large;
      background-color: greenyellow;
      padding: 10px;
      cursor: pointer;
      border-radius: 50px;
    }

    .container-feedbacks {
      display: grid;
      grid-column: auto auto auto;
    }

    .feedback-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      padding: 28px;
      border-radius: 16px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      transition: all 0.4s ease;
      border-left: 6px solid #3498db;
      position: relative;
      overflow: hidden;
      width: 500px;
      margin: 0 auto;
    }

    .feedback-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(to right, #3498db, #27ae60);
      opacity: 0;
      transition: opacity 0.3s;
    }

    .feedback-card:hover::before {
      opacity: 1;
    }

    .feedback-card:hover {
      transform: translateY(-12px) scale(1.02);
      box-shadow: 0 18px 35px rgba(52, 152, 219, 0.2);
      border-left-color: #27ae60;
    }

    .feedback-card p {
      margin-bottom: 14px;
      font-size: 1rem;
      position: relative;
      padding-left: 10px;
    }

    .feedback-card p strong {
      color: #2c3e50;
      font-weight: 600;
      display: inline-block;
      width: 70px;
    }

    .feedback-card p:nth-child(1) {
      font-size: 1.2rem;
      font-weight: bold;
      color: #2c3e50;
      margin-bottom: 16px;
      padding-left: 0;
    }

    .feedback-card p:nth-child(1)::before {
      content: '👤';
      margin-right: 8px;
      font-size: 1.3rem;
    }

    .feedback-card p:nth-child(2)::before {
      content: '✉️';
      margin-right: 8px;
    }

    .feedback-card p:nth-child(3) {
      font-style: italic;
      color: #555;
      line-height: 1.5;
      margin-bottom: 18px;
      padding: 12px;
      background-color: #f1f3f5;
      border-radius: 8px;
      border-left: 4px solid #3498db;
    }

    .feedback-card p:nth-child(3)::before {
      content: '💬';
      margin-right: 8px;
      font-size: 1.1rem;
    }

    .date {
      font-size: 0.85rem;
      color: #95a5a6;
      text-align: right;
      margin-top: 20px;
      font-style: normal;
      font-weight: 500;
      padding-right: 5px;
      position: relative;
    }

    .date::before {
      content: '🕒';
      margin-right: 6px;
      font-size: 0.9rem;
    }

    .date::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 2px;
      background-color: #bdc3c7;
      border-radius: 2px;
    }

    /* Optional: Star rating placeholder (if you add ratings later) */
    .feedback-card::after {
      position: absolute;
      top: 12px;
      right: 12px;
      font-size: 1rem;
      color: #f1c40f;
      opacity: 0.7;
      letter-spacing: 2px;
    }

    /* Responsive tweak for smaller cards */
    @media (max-width: 480px) {
      .feedback-card {
        padding: 20px;
        border-radius: 12px;
      }

      .feedback-card p strong {
        width: 60px;
        font-size: 0.95rem;
      }

      .feedback-card p:nth-child(3) {
        padding: 10px;
        font-size: 0.95rem;
      }
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

  <!-- Feedback Form -->
  <section class="form-of-feedback">
    <div class="container-feedback">
      <div class="inner-feedback">
        <h1 style="text-align: center;margin-bottom: 10px;">Give Us Your Feedback</h1>
        <form method="POST" class="feedback-body">
          <div class="box">
            <p>Enter Name:</p>
            <input type="text" name="name" required>
          </div>

          <div class="box">
            <p>Enter Email:</p>
            <input type="email" name="email" required>
          </div>

          <div class="box">
            <p>Enter Feedback:</p>
            <textarea name="message" required></textarea>
          </div>

          <button type="submit" name="submit_feedback" class="submit-btn">Submit</button>
        </form>
      </div>
    </div>
  </section>

  <h1 style="text-align: center; margin-top: 60px; margin-bottom: 90px;">Reviews of Customers</h1>

  <!-- Feedbacks -->
  <div class="container-feedbacks">
    <?php if (count($feedbacks) == 0): ?>
      <p style="text-align:center;">No feedback yet.</p>
    <?php else: ?>
      <?php foreach ($feedbacks as $fb): ?>
        <div class="feedback-card">
          <p><strong></strong> <?= htmlspecialchars($fb['name']) ?></p>
          <p><strong></strong> <?= htmlspecialchars($fb['email']) ?></p>
          <p><strong style="margin-right: 20px;">Feedback:</strong> <?= htmlspecialchars($fb['message']) ?></p>
          <p class="date"><?= date("d M Y H:i", strtotime($fb['created_at'])) ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>


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

  <script src="/js/main.js"></script>
</body>

</html>