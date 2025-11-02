<?php
require_once 'config/db_connection.php';
session_start();

// Get total from URL
$total = isset($_GET['total']) ? $_GET['total'] : 0;

// Encryption setup (for demonstration)
define('ENCRYPTION_KEY', 'your-strong-secret-key-here');
define('ENCRYPTION_METHOD', 'AES-256-CBC');

// Encrypt function
function encryptData($data) {
  $key = hash('sha256', ENCRYPTION_KEY);
  $iv = substr(hash('sha256', 'iv1234567890'), 0, 16);
  return openssl_encrypt($data, ENCRYPTION_METHOD, $key, 0, $iv);
}

// Handle form submission
if (isset($_POST['pay_now'])) {
  $card_name = mysqli_real_escape_string($conn, $_POST['card_name']);
  $card_number = mysqli_real_escape_string($conn, $_POST['card_number']);
  $exp_date = mysqli_real_escape_string($conn, $_POST['exp_date']);
  $cvv = mysqli_real_escape_string($conn, $_POST['cvv']);
  $total = mysqli_real_escape_string($conn, $_POST['total']);

  // Encrypt sensitive data
  $encrypted_card = encryptData($card_number);
  $encrypted_cvv = encryptData($cvv);

  // Current logged-in user
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // For testing

  // Insert payment record
  $payment_sql = "INSERT INTO payments (user_id, exp_date, total, status)
                  VALUES ('$user_id', '$exp_date', '$total', 'completed')";

  if (mysqli_query($conn, $payment_sql)) {

    // ✅ Begin transaction for safety
    mysqli_begin_transaction($conn);

    try {
      // Fetch all cart items
      $cart_items = mysqli_query($conn, "
        SELECT c.book_id, c.quantity, b.price 
        FROM cart c
        JOIN books b ON c.book_id = b.id
        WHERE c.user_id = '$user_id'
      ");

      if (mysqli_num_rows($cart_items) > 0) {
        while ($item = mysqli_fetch_assoc($cart_items)) {
          $book_id = $item['book_id'];
          $quantity = $item['quantity'];
          $book_total = $item['price'] * $quantity;

          // ✅ Insert into orders table
          $insert_order = "
            INSERT INTO orders (user_id, book_id, quantity, total)
            VALUES ('$user_id', '$book_id', '$quantity', '$book_total')
          ";
          mysqli_query($conn, $insert_order);
        }

        // ✅ Clear cart after successful order
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");
      }

      // Commit order transaction
      mysqli_commit($conn);

      echo "
        <script>
          window.location.href = 'thankyou.php';
        </script>
      ";
      exit;

    } catch (Exception $e) {
      mysqli_rollback($conn);
      echo "Error while saving order: " . $e->getMessage();
    }

  } else {
    echo "Payment Error: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Payment Page</title>
  <link rel='stylesheet' href='css/payment.css'>
</head>
<body>
  <div class='payment-container'>
    <h2>Payment Details</h2>
    <form class='payment-form' method='POST'>
      <label>Cardholder Name</label>
      <input type='text' name='card_name' placeholder='Enter Card Name' required>

      <label>Card Number</label>
      <input type='text' name='card_number' placeholder='1234 5678 9012 3456' required>

      <div class='card-details'>
        <div>
          <label>Expiry Date</label>
          <input type='text' name='exp_date' placeholder='MM/YY' required>
        </div>
        <div>
          <label>CVV</label>
          <input type='text' name='cvv' placeholder='123' required>
        </div>
      </div>

      <label>Total Amount (Rs.)</label>
      <input type='text' value='<?php echo htmlspecialchars($total); ?>' readonly>
      <input type='hidden' name='total' value='<?php echo htmlspecialchars($total); ?>'>

      <button type='submit' name='pay_now' class='payButton'>Pay Now</button>
    </form>

    <div class='payment-summary'>
      <p>Your total payment amount is <strong>Rs. <?php echo htmlspecialchars($total); ?></strong></p>
    </div>
  </div>
</body>
</html>
