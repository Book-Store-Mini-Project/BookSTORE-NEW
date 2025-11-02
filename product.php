<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>READIFY Bookstore</title>
  <link rel="icon" type="image/png" href="/assets/img-title.png">

  <link rel="stylesheet" href="./css/common.css" />
  <link rel="stylesheet" href="./css/product.css" />
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

  <div class="content">
    <?php
    require_once 'config/db_connection.php';
    $conn->set_charset("utf8"); // Ensure Sinhala text works
    // Get books for each category
    $categories = ['English', 'Sinhala', 'Tamil'];

    foreach ($categories as $category) {
      $sql = "SELECT * FROM books WHERE category = '$category'";
      $result = mysqli_query($conn, $sql);

      if ($result && mysqli_num_rows($result) > 0) {
        echo "<h1>$category</h1><hr />";
        echo "<div class='container'>";

        while ($book = mysqli_fetch_assoc($result)) {
          echo "
                <div class='containerCard'>
                    <div class='imageBox'> 
                        <img src='{$book['imageUrl']}' class='img' alt='{$book['bookName']}'>
                    </div>
                    <div class='productDetail'>
                        <p class='bookname'>{$book['bookName']}</p>
                        <div class='bookauthor'>
                            <p class='author'>Author : {$book['author']}</p>
                        </div>
                        <p class='price'>Rs. {$book['price']}</p>
                        <p class='description'>{$book['description']}</p>
                        <a href='cart.php?book_id={$book['id']}'>
                            <button class='addCart'>Add to Cart</button>
                        </a>
                    </div>
                </div>";
        }
        echo "</div>";
      }
    }

    mysqli_close($conn);
    ?>


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