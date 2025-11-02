<?php
session_start();
require_once 'config/db_connection.php';

// =============================
// FETCH FEATURED BOOKS (last 5)
// =============================
$featuredBooks = [];
$result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC LIMIT 5");
while ($row = mysqli_fetch_assoc($result)) {
  $featuredBooks[] = $row;
}

// =============================
// FETCH NEW ARRIVALS (all books)
// =============================
$newBooks = [];
$result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC LIMIT 3");
while ($row = mysqli_fetch_assoc($result)) {
  $newBooks[] = $row;
}

// =============================
// FETCH FAQS
// =============================
$faqs = [];
$result = mysqli_query($conn, "SELECT * FROM faq ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($result)) {
  $faqs[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>READIFY Bookstore</title>
  <link rel="icon" type="image/png" href="/assets/img-title.png" />
  <link rel="stylesheet" href="./css/common.css" />
  <link rel="stylesheet" href="./css/home.css" />
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
  
  <!-- Header -->
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

        <!-- ADMIN LINK: Only show if logged-in user email is exactly 'admin@gmail.com' -->
        <?php if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@gmail.com'): ?>
          <li><a href="./admin.php">DashBorad</a></li>
        <?php endif; ?>

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


  <!--Slider Section -->
  <div class="container-slider">
    <div class="inner-container-slider">
      <div class="slideshow-container">
        <div class="mySlides fade">
          <img src="./assets/caro-image/caro01.webp" style="width: 100%" alt="..." height="500px" />
        </div>

        <div class="mySlides fade">
          <img src="./assets/caro-image/caro-02.jpg" style="width: 100%" alt="..." height="500px" />
        </div>

        <div class="mySlides fade">
          <img src="./assets/caro-image/caro03.jpeg" style="width: 100%" alt="..." height="500px" />
        </div>
      </div>
      <br />

      <!-- The dots/circles -->
      <div style="text-align: center">
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
      </div>
    </div>
  </div>

  <div class="container-info">
    <div class="container-info-box">
      <img src="./assets/shipping-icon.png" alt="..." class="image-info-resize" />
      <p>Free Shipping</p>
      <p>Order More than $100</p>
    </div>

    <div class="container-info-box">
      <img src="./assets/secure-icon.png" alt="..." class="image-info-resize" />
      <p>Secure Payment</p>
      <p>100% Secure Payment</p>
    </div>

    <div class="container-info-box">
      <img src="./assets/support-icon.png" alt="..." class="image-info-resize" />
      <p>24/7 Support</p>
      <p>Call us anytime</p>
    </div>

    <div class="container-info-box">
      <img src="./assets/user-interface.png" alt="..." class="image-info-resize" />
      <p>Eassy To Access</p>
      <p>User Friendly</p>
    </div>
  </div>

  <!-- Featured Books -->
  <section id="featured" class="section">
    <h2 style="text-align: center;">Featured Books</h2>
    <div class="container-Book container-New-Book">
      <?php foreach ($featuredBooks as $book): ?>
        <div class="containerCard">
          <div class="imageBox">
            <img src="<?= htmlspecialchars($book['imageUrl']) ?>" class="card-img" />
          </div>
          <div class="productDetail">
            <p class="bookname"><?= htmlspecialchars($book['bookName']) ?></p>
            <div class="bookauthor">
              <p class="bookauthor">Author : <?= htmlspecialchars($book['author']) ?></p>
            </div>
            <p class="price">Rs.<?= htmlspecialchars($book['price']) ?></p>
            <p class="rate">Ratings : 5.0⭐</p>
            <button class="addCart"
              data-id="<?= $book['id'] ?>"
              data-title="<?= htmlspecialchars($book['bookName']) ?>"
              data-price="<?= htmlspecialchars($book['price']) ?>"
              data-image="<?= htmlspecialchars($book['imageUrl']) ?>">
              Add to Cart
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!--mini info for discount -->
  <div class="container-discount">
    <div class="left">
      <h3>Browse &</h3>
      <h4>Select Your Choices</h4>
      <p>
        Find the best books from your favorite writers,explore hundreds of
        books with all possible,categories, take advantage of the 50% discount
        and musch more.
      </p>
    </div>
    <div class="right">
      <img src="./assets/discount-image.png" alt="..." />
    </div>
  </div>



  <div class="container-info-mini">
    <div class="info">
      <div class="info1">
        Dive into a curated collection that spans every genre and interest.
        From timeless classics and bestselling novels to academic resources,
        self-help guides, and children’s books we’ve got something for every
        reader. Discover new authors, explore unique perspectives, and enrich
        your bookshelf with selections that inspire, educate, and entertain.
        Whether you’re a casual reader or a passionate bibliophile, our
        diverse book range is sure to captivate your curiosity.
      </div>
      <img src="./assets/mini01.jpg" alt="" class="image-resize-mini" />
    </div>

    <div class="info">
      <img src="./assets/mini02.jpg" alt="" class="image-resize-mini" />
      <div class="info1">
        Reading books is important for personal growth and learning. It
        improves vocabulary, focus, and thinking skills. Books introduce new
        ideas, cultures, and viewpoints, helping us understand the world
        better. They also reduce stress and offer a healthy escape from daily
        life. Reading boosts creativity and imagination, especially in young
        minds. It supports academic success and improves communication. Even
        in today’s digital world, reading remains a powerful habit that
        inspires, educates, and shapes individuals in meaningful ways.
      </div>
    </div>

    <div class="info">
      <div class="info1">
        Our bookshop features a cozy and inviting reading area, perfect for
        book lovers to relax and enjoy their favorite stories. Soft lighting,
        comfortable seating, and a peaceful atmosphere create the ideal space
        to get lost in a good book. Surrounded by shelves of carefully chosen
        titles, visitors can read at their own pace without distractions. It’s
        a quiet corner where imagination flows freely, making every visit to
        our shop not just about buying books, but experiencing them.
      </div>
      <img src="./assets/mini03.jpg" alt="" class="image-resize-mini" />
    </div>
  </div>


  <!-- New Arrivals -->
  <h1 style="text-align: center; margin-bottom: 40px;">New Arrivals</h1>
  <div class="container-Book container-New-Book">
    <?php foreach ($newBooks as $book): ?>
      <div class="containerCard">
        <div class="imageBox">
          <img src="<?= htmlspecialchars($book['imageUrl']) ?>" class="card-img" />
        </div>
        <div class="productDetail">
          <p class="bookname"><?= htmlspecialchars($book['bookName']) ?></p>
          <div class="bookauthor">
            <p class="bookauthor">Author : <?= htmlspecialchars($book['author']) ?></p>
          </div>
          <p class="price">Rs.<?= htmlspecialchars($book['price']) ?></p>
          <p class="rate">Ratings : 5.0⭐</p>
          <button class="addCart"
            data-id="<?= $book['id'] ?>"
            data-title="<?= htmlspecialchars($book['bookName']) ?>"
            data-price="<?= htmlspecialchars($book['price']) ?>"
            data-image="<?= htmlspecialchars($book['imageUrl']) ?>">
            Add to Cart
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>


  <!--bottom box -->
  <div class="container-bottom-box">
    <h1 style="text-transform: uppercase">Shopping with us</h1>
    <a href="./product.php"><button class="button-shop-now">Shop Now</button></a>
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