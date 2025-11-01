<?php
require "./config/db_connection.php";

// Create table if not exists
$conn->query("
  CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_date DATETIME DEFAULT CURRENT_TIMESTAMP
);
");

// Create table if not exists
$conn->query("
 CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  title VARCHAR(255),
  price DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id)
);
");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $book_name = trim($_POST['book_name']);
  $total_price = (float)$_POST['total_price'];

  if (!empty($book_name) && $total_price > 0) {
    $stmt = $conn->prepare("INSERT INTO orders (book_name, total_price) VALUES (?, ?)");
    $stmt->bind_param("sd", $book_name, $total_price);
    if ($stmt->execute()) {
      echo "<script>alert('Order placed successfully!');</script>";
    } else {
      echo "<script>alert('Error placing order. Please try again.');</script>";
    }
    $stmt->close();
  } else {
    echo "<script>alert('Please enter book name and total price!');</script>";
  }
}

// Fetch the last order
$result = $conn->query("SELECT * FROM orders ORDER BY order_id DESC LIMIT 1");
$last_order = $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>READIFY Bookstore - Order</title>
  <link rel="icon" type="image/png" href="/assets/img-title.png">
  <link rel="stylesheet" href="css/common.css" />
  <link rel="stylesheet" href="css/home.css" />
  <link rel="stylesheet" href="css/order.css" />
</head>

<body>
  <!-- Header -->
  <header class="header">
    <div class="container-inner">
      <a href="index.html" class="logo-link">
        <div class="logo">
          <h1>READIFY</h1>
          <img src="./assets/title.png" alt="BookShop Logo" class="logo-resize" />
        </div>
      </a>
      <button class="hamburger" id="hamburger">&#9776;</button>
      <nav class="nav-link" id="nav">
        <button class="close-icon" id="close-icon">&times;</button>
        <ul>
          <li><a href="./index.html">Home</a></li>
          <li><a href="./product.php">Books</a></li>
          <li><a href="./aboutus.php">About Us</a></li>
          <li><a href="./contact.php">Contact Us</a></li>
          <li><a href="./cart.html">Cart</a></li>
          <li><a href="./orders.php">Orders</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <h1 style="text-align:center;margin-top:50px;margin-bottom:30px;">Place Your Order</h1>

  <!-- Order Form -->
  <div style="text-align:center; margin-bottom:50px;">
    <form method="POST" style="display:inline-block; text-align:left; padding:20px; border:1px solid #ccc; border-radius:10px;">
      <label>Book Name:</label><br>
      <input type="text" name="book_name" required><br><br>

      <label>Total Price (Rs.):</label><br>
      <input type="number" name="total_price" step="0.01" required><br><br>

      <button type="submit">Place Order</button>
    </form>
  </div>

  <!-- Last Order Summary -->
  <div id="lastOrder" style="text-align:center; margin-bottom:100px;">
    <?php if ($last_order): ?>
      <h2>Last Order Summary</h2>
      <p><strong>Book:</strong> <?= htmlspecialchars($last_order['book_name']); ?></p>
      <p><strong>Total Price:</strong> Rs.<?= htmlspecialchars($last_order['total_price']); ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($last_order['order_date']); ?></p>
    <?php else: ?>
      <p>No orders placed yet.</p>
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
          <li><a href="./orders.php">Orders</a></li>

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