<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>READIFY Bookstore</title>
  <link rel="icon" type="image/png" href="/assets/img-title.png">

  <link rel="stylesheet" href="./css/common.css" />
  <link rel="stylesheet" href="./css/cartstyle.css.css" />
  <script src="./js/cart.js"></script>
</head>

<body>
  <!--Header -->
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
          <li><a href="./index.php">Home</a></li>
          <li><a href="./product.php">Books</a></li>
          <li><a href="./aboutus.php">About Us</a></li>
          <li><a href="./contact.php">Contact Us</a></li>
          <li><a href="./cart.php">Cart</a></li>

           <li id="user-info"></li>
          <li><a href="./loginPage.php" id="auth-action">Sign In</a></li>

        </ul>

      </nav>
    </div>
  </header>

<?php
require "./config/db_connection.php";

// 🧹 DELETE item
if (isset($_GET['delete'])) {
    $book_id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM cart WHERE book_id = $book_id");
    echo "<script>window.location = 'cart.php';</script>";
    exit;
}

// ➕ Increment quantity
if (isset($_GET['increase'])) {
    $book_id = (int) $_GET['increase'];
    mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE book_id = $book_id");
    echo "<script>window.location = 'cart.php';</script>";
    exit;
}

// ➖ Decrement quantity (but not below 1)
if (isset($_GET['decrease'])) {
    $book_id = (int) $_GET['decrease'];
    $check = mysqli_query($conn, "SELECT quantity FROM cart WHERE book_id = $book_id");
    $row = mysqli_fetch_assoc($check);
    if ($row && $row['quantity'] > 1) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity - 1 WHERE book_id = $book_id");
    } else {
        // If quantity would drop below 1, remove it from cart
        mysqli_query($conn, "DELETE FROM cart WHERE book_id = $book_id");
    }
    echo "<script>window.location = 'cart.php';</script>";
    exit;
}

// 🛒 Add item to cart
if (isset($_GET['book_id'])) {
    $book_id = (int) $_GET['book_id'];

    $check_sql = "SELECT cart_id, quantity FROM cart WHERE book_id = $book_id";
    $result = mysqli_query($conn, $check_sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $new_quantity = $row['quantity'] + 1;
        $update_sql = "UPDATE cart SET quantity = $new_quantity WHERE cart_id = " . $row['cart_id'];
        mysqli_query($conn, $update_sql);
    } else {
        $sql_book = "SELECT imageURL FROM books WHERE id = $book_id";
        $book_result = mysqli_query($conn, $sql_book);

        if ($book_result && mysqli_num_rows($book_result) > 0) {
            $book_row = mysqli_fetch_assoc($book_result);
            $image_path = $book_row['imageURL'];
        } else {
            $image_path = "";
        }

        $insert_sql = "INSERT INTO cart (book_id, quantity, image_path) VALUES ($book_id, 1, '$image_path')";
        mysqli_query($conn, $insert_sql);
    }

    echo "<script>window.location = 'cart.php';</script>";
    exit;
}

// 📦 Fetch cart details
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
";
$cart_result = mysqli_query($conn, $cart_sql);
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cart</title>
  <link rel="stylesheet" href="cart.css">
</head>
<body>

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
              style="display: flex; justify-content: center; align-items: center; text-decoration: none; text-align: center;">-</a>
              <span><?php echo $row['quantity']; ?></span>
              <a href="cart.php?increase=<?php echo $row['book_id']; ?>" class="btn-plus"   
              style="display: flex; justify-content: center; align-items: center; text-decoration: none; text-align: center;">+</a>
            
</div>
            <p class="total">Total: Rs. <?php echo number_format($row['total'], 2); ?></p>

            <a href="cart.php?delete=<?php echo $row['book_id']; ?>"
               class="delete-btn"
               onclick="return confirm('Remove this item from cart?');"
               style="display: flex; justify-content: center; align-items: center; text-decoration: none; text-align: center;">
               Remove
            </a>
          </div>
        </div>
        <?php $total_price += $row['total']; ?>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Your cart is empty.</p>
    <?php endif; ?>
  </div>
</div>

<div class="place-order">
  <h2>Your Total Price is:</h2>
  <h2>Rs. <span id="price"><?php echo number_format($total_price, 2); ?></span></h2>
  <button class="place-order-btn" id="place-order-btn">Place Order</button>
</div>

</body>
</html>

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