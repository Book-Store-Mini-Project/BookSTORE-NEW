<?php
require_once 'config/db_connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Fetch ordered items for this user
$sql = "
  SELECT b.bookName, o.quantity, o.total, o.order_date
  FROM orders o
  JOIN books b ON o.book_id = b.id
  WHERE o.user_id = '$user_id'
  ORDER BY o.order_date DESC
";
$result = mysqli_query($conn, $sql);

// Calculate total spent
$total_amount = 0;
if (mysqli_num_rows($result) > 0) {
  // Move pointer and sum totals
  mysqli_data_seek($result, 0);
  while ($row = mysqli_fetch_assoc($result)) {
    $total_amount += $row['total'];
  }
  // Reset pointer to display again
  mysqli_data_seek($result, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Orders</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
    }
    .orders-container {
      width: 80%;
      margin: 60px auto;
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #007bff;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .total-section {
      text-align: right;
      margin-top: 15px;
      font-size: 18px;
      font-weight: 600;
      color: #333;
    }
    .no-orders {
      text-align: center;
      padding: 40px;
      color: #555;
    }
    .home-btn {
      display: block;
      width: fit-content;
      margin: 25px auto 0;
      padding: 10px 25px;
      background-color: #007bff;
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s;
    }
    .home-btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <div class="orders-container">
    <h2>Your Ordered Items</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Book Name</th>
            <th>Quantity</th>
            <th>Total (Rs.)</th>
            <th>Order Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['bookName']); ?></td>
              <td><?php echo htmlspecialchars($row['quantity']); ?></td>
              <td><?php echo htmlspecialchars(number_format($row['total'], 2)); ?></td>
              <td><?php echo htmlspecialchars($row['order_date']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="total-section">
        Total Spent: Rs. <?php echo number_format($total_amount, 2); ?>
      </div>
    <?php else: ?>
      <div class="no-orders">
        <p>You haven’t placed any orders yet.</p>
      </div>
    <?php endif; ?>

    <a href="index.php" class="home-btn">Back to Home</a>
  </div>

</body>
</html>
