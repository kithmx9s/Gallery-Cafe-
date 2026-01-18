<?php
include "db_connect.php";
$sql = "SELECT * FROM food_items ORDER BY country, category, name";
$result = $conn->query($sql);

// Group items by country
$itemsByCountry = [];
while ($row = $result->fetch_assoc()) {
    $itemsByCountry[$row['country']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="assets/logo.png">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="nav-logo">
            <img src="assets/logo.png" alt="">
            <span>Gallery Cafe</span>
        </div>

        <ul class="nav-links">
          <li><a href="index.html" class="active">Home</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="reservation.php">Reservation</a></li>
          <li><a href="event.html">Events</a></li>
          <li><a href="about.html">About Us</a></li>
        </ul>

        <div class="nav-icons">
            <a href="#"><i class="fa-solid fa-user"></i></a>
            <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
        </div>

        <div class="menu-toggle" id="menu-toggle">
            <i class="fa-solid fa-bars"></i>
        </div>
    </nav>
</header>

<section class="category-section">
    <div class="category-tabs">
        <button class="category-btn active" data-category="srilankan">Sri Lankan</button>
        <button class="category-btn" data-category="indian">Indian</button>
        <button class="category-btn" data-category="chinese">Chinese</button>
        <button class="category-btn" data-category="arabian">Arabian</button>
        <button class="category-btn" data-category="mongolian">Mongolian</button>
        <button class="category-btn" data-category="french">French</button>
        <button class="category-btn" data-category="italian">Italian</button>
    </div>
</section>



<section class="menu-items">
    <?php foreach ($itemsByCountry as $country => $items): ?>
        <div class="items-grid country-block" id="<?php echo $country; ?>">
            <?php foreach ($items as $food): ?>
                <div class="menu-card">
                    <img src="images/<?php echo $food['image']; ?>" alt="">
                    <h3><?php echo $food['name']; ?></h3>
                    <p class="desc"><?php echo $food['description']; ?></p>
                    <p class="price">LKR <?php echo number_format($food['price'], 2); ?></p>
                    <button class="order-btn">Order Now</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</section>

<!-- ===== FOOTER SECTION ===== -->
<footer class="footer">
  <div class="footer-container">
    <!-- About -->
    <div class="footer-section about">
      <h3>Gallery Cafe</h3>
      <p>
        Serving the world’s flavors under one cozy roof. From coffee to pastries,
        every bite tells a story. Come taste the journey.
      </p>
    </div>

    <!-- Quick Links -->
    <div class="footer-section links">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="#reservation">Reservation</a></li>
        <li><a href="#events">Events</a></li>
        <li><a href="about.html">About Us</a></li>
      </ul>
    </div>

    <!-- Contact -->
    <div class="footer-section contact">
      <h3>Contact Us</h3>
      <p>Email: info@gallerycafe.com</p>
      <p>Phone: +94 77 123 4567</p>
      <p>Address: 123 Café Street, Colombo, Sri Lanka</p>
    </div>

    <!-- Social Media -->
    <div class="footer-section social">
      <h3>Follow Us</h3>
      <div class="social-icons">
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    &copy; 2025 Gallery Café. All Rights Reserved.
  </div>

<script src="js/menu.js"></script>
</body>
</html>
