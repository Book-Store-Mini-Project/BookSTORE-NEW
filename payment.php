<?php
require_once 'config/db_connection.php'; // your DB connection

// Fetch the latest order
$orderQuery = "SELECT * FROM orders ORDER BY id DESC LIMIT 1";
$orderResult = mysqli_query($conn, $orderQuery);
$order = mysqli_fetch_assoc($orderResult);

if ($order) {
  $order_id = $order['id'];
  $order_date = $order['order_date'];

  // Get all order items
  $itemQuery = "SELECT * FROM order_items WHERE order_id = $order_id";
  $itemResult = mysqli_query($conn, $itemQuery);

  $subtotal = 0;
  $items = [];
  while ($item = mysqli_fetch_assoc($itemResult)) {
    $items[] = $item;
    $subtotal += $item['price'];
  }

  $tax = $subtotal * 0.10;
  $total = $subtotal + $tax;
} else {
  $order = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment Page</title>
  <link rel="stylesheet" href="css/payment.css" />
</head>

<body>
  <div class="payment-container">
    <h2>Payment Details</h2>

    <?php if (!$order): ?>
      <p>No orders found. Please place an order first.</p>
    <?php else: ?>
      <form class="payment-form" method="POST">
        <label>Cardholder Name</label>
        <input type="text" name="card_name" placeholder="Enter Card Name" required />

        <label>Card Number</label>
        <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required />

        <div class="card-details">
          <div>
            <label>Expiry Date</label>
            <input type="text" name="exp_date" placeholder="MM/YY" required />
          </div>
          <div>
            <label>CVV</label>
            <input type="text" name="cvv" placeholder="123" required />
          </div>
        </div>

        <h3>Order Summary</h3>
        <p><strong>Order Date:</strong> <?= date('Y-m-d H:i', strtotime($order_date)) ?></p>
        <ul>
          <?php foreach ($items as $index => $item): ?>
            <li><?= $index + 1 ?>. <?= htmlspecialchars($item['title']) ?> - Rs. <?= number_format($item['price'], 2) ?></li>
          <?php endforeach; ?>
        </ul>
        <p>Subtotal: Rs. <?= number_format($subtotal, 2) ?></p>
        <p>Credit Card Tax (10%): Rs. <?= number_format($tax, 2) ?></p>
        <hr />
        <p class="total"><strong>Total: Rs. <?= number_format($total, 2) ?></strong></p>

        <button type="submit" name="pay_now" class="payButton">Pay Now</button>
      </form>
    <?php endif; ?>
  </div>
</body>

</html>

<?php
// Handle payment submission
if (isset($_POST['pay_now'])) {
  $card_name = $_POST['card_name'];
  $card_number = $_POST['card_number'];
  $exp_date = $_POST['exp_date'];
  $cvv = $_POST['cvv'];

  // Save payment details
  $sql = "INSERT INTO payments (order_id, card_name, card_number, exp_date, cvv, total)
            VALUES ('$order_id', '$card_name', '$card_number', '$exp_date', '$cvv', '$total')";
  mysqli_query($conn, $sql);

  // Redirect to thank you page
  header("Location: thankyou.php");
  exit;
}
?>



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