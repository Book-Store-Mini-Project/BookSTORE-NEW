<?php

require "./config/db_connection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please sign in to access your cart.'); window.location = 'loginPage.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';

// 🧹 DELETE item
if (isset($_GET['delete'])) {
    $book_id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id AND book_id = $book_id");
    echo "<script>window.location = 'cart.php';</script>";
    exit;
}

// ➕ INCREASE quantity
if (isset($_GET['increase'])) {
    $book_id = (int) $_GET['increase'];
    mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND book_id = $book_id");
    echo "<script>window.location = 'cart.php';</script>";
    exit;
}

// ➖ DECREASE quantity (not below 1)
if (isset($_GET['decrease'])) {
    $book_id = (int) $_GET['decrease'];
    $check = mysqli_query($conn, "SELECT quantity FROM cart WHERE user_id = $user_id AND book_id = $book_id");
    $row = mysqli_fetch_assoc($check);
    if ($row && $row['quantity'] > 1) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity - 1 WHERE user_id = $user_id AND book_id = $book_id");
    } else {
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id AND book_id = $book_id");
    }
    echo "<script>window.location = 'cart.php';</script>";
    exit;
}

// 🛒 ADD item to cart
if (isset($_GET['book_id'])) {
    $book_id = (int) $_GET['book_id'];

    $check_sql = "SELECT cart_id, quantity FROM cart WHERE user_id = $user_id AND book_id = $book_id";
    $result = mysqli_query($conn, $check_sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $new_quantity = $row['quantity'] + 1;
        $update_sql = "UPDATE cart SET quantity = $new_quantity WHERE cart_id = " . $row['cart_id'];
        mysqli_query($conn, $update_sql);
    } else {
        $sql_book = "SELECT imageURL FROM books WHERE id = $book_id";
        $book_result = mysqli_query($conn, $sql_book);
        $image_path = "";

        if ($book_result && mysqli_num_rows($book_result) > 0) {
            $book_row = mysqli_fetch_assoc($book_result);
            $image_path = $book_row['imageURL'];
        }

        $insert_sql = "INSERT INTO cart (user_id, book_id, quantity, image_path) VALUES ($user_id, $book_id, 1, '$image_path')";
        mysqli_query($conn, $insert_sql);
    }

    echo "<script>window.location = 'cart.php';</script>";
    exit;
}

// 📦 FETCH user cart details
$cart_sql = "
  SELECT 
      cart.cart_id, 
      cart.book_id, 
      books.bookName, 
      books.price, 
      cart.image_path, 
      cart.quantity, 
      (books.price * cart.quantity) AS total
  FROM cart
  JOIN books ON cart.book_id = books.id
  WHERE cart.user_id = $user_id
";
$cart_result = mysqli_query($conn, $cart_sql);
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>READIFY Bookstore - Cart</title>
  <link rel="icon" type="image/png" href="/assets/img-title.png">
  <link rel="stylesheet" href="./css/common.css" />
  <link rel="stylesheet" href="./css/cart.css" />
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

  <!-- Cart Items -->
  <div class="container-cart-items">
    <div class="inner-cart" id="inner-cart">
      <?php if ($cart_result && mysqli_num_rows($cart_result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($cart_result)): ?>
          <div class="cart-Card">
            <div class="cart-item">

              <h3 class="description"><?php echo htmlspecialchars($row['bookName']); ?></h3>

              <?php if (!empty($row['image_path'])): ?>
                <img class="image-resize"
                     src="<?php echo htmlspecialchars($row['image_path']); ?>"
                     alt="<?php echo htmlspecialchars($row['bookName']); ?>">
              <?php endif; ?>

              <p class="price">Price: Rs. <?php echo number_format($row['price'], 2); ?></p>

              <div class="btn-increment">
                <a href="cart.php?decrease=<?php echo $row['book_id']; ?>" class="btn-minus"
                   style="display:flex;justify-content:center;align-items:center;text-decoration:none;">-</a>
                <span><?php echo $row['quantity']; ?></span>
                <a href="cart.php?increase=<?php echo $row['book_id']; ?>" class="btn-plus"
                   style="display:flex;justify-content:center;align-items:center;text-decoration:none;">+</a>
              </div>

              <p class="total">Total: Rs. <?php echo number_format($row['total'], 2); ?></p>

              <a href="cart.php?delete=<?php echo $row['book_id']; ?>"
                 class="delete-btn"
                 onclick="return confirm('Remove this item from cart?');"
                 style="display:flex;justify-content:center;align-items:center;text-decoration:none;">
                 Remove
              </a>
            </div>
          </div>
          <?php $total_price += $row['total']; ?>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="text-align: center;margin-top:130px;font-size: large;margin-bottom:130px;">Your cart is empty.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Order Summary -->
  <div class="place-order">
    <h2>Your Total Price is:</h2>
    <h2>Rs. <span id="price"><?php echo number_format($total_price, 2); ?></span></h2>
    <button class="place-order-btn" id="place-order-btn" 
            onclick="window.location.href='payment.php?total=<?php echo $total_price; ?>'">
      Place Order
    </button>
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
  <script src="js/main.js"></script>
  <script src="js/home.js"></script>
</body>
</html>
