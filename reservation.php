<?php
// reservation.php
// Page shows realtime stats and the reservation form.
// Ensure db_connect.php is available in same folder and working.

include 'db_connect.php';

// Fetch distinct meal types and countries from food_items for dropdowns
$mealTypes = [];
$countries = [];

$res = $conn->query("SELECT DISTINCT category FROM food_items");
if($res){
    while($r = $res->fetch_assoc()) $mealTypes[] = $r['category'];
}

$res2 = $conn->query("SELECT DISTINCT country FROM food_items");
if($res2){
    while($r = $res2->fetch_assoc()) $countries[] = $r['country'];
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Reserve Table | Gallery Café</title>
  <link rel="stylesheet" href="css/reservation.css">
  <script defer src="js/reservation.js"></script>
</head>
<body>
  <header class="res-header">
    <nav class="navbar">
      <div class="nav-logo">
        <img src="/mnt/data/bfa75754-10dd-45dc-910f-2ecc57b99e44.png" alt="hero-img" class="header-img">
        <span>Gallery Café</span>
      </div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a class="active" href="reservation.php">Reservations</a></li>
        <li><a href="about.php">About</a></li>
      </ul>
    </nav>
  </header>

  <main class="container">
    <section class="stats-panel">
      <h2>Table Availability</h2>
      <div class="stats-row">
        <div class="stat-card total">
          <h3>Total Tables</h3>
          <p class="stat-value" id="total-tables">50</p>
        </div>

        <div class="stat-card reserved">
          <h3>Reserved</h3>
          <p class="stat-value" id="reserved-count">0</p>
        </div>

        <div class="stat-card left">
          <h3>Available</h3>
          <p class="stat-value" id="available-count">50</p>
        </div>
      </div>

      <div class="date-row">
        <label for="stats-date">Check date:</label>
        <input type="date" id="stats-date" name="stats-date" value="<?php echo date('Y-m-d'); ?>">
        <label for="stats-time">Time:</label>
        <input type="time" id="stats-time" name="stats-time" value="<?php echo date('H:i'); ?>">
        <button id="refresh-stats" class="btn">Refresh</button>
      </div>
    </section>

    <section class="form-panel">
      <h2>Make a Reservation</h2>

      <form id="reservation-form">
        <div class="row">
          <label for="name">Full Name</label>
          <input id="name" name="name" type="text" required placeholder="John Doe">
        </div>

        <div class="row">
          <label for="phone">Phone Number</label>
          <input id="phone" name="phone" type="tel" required placeholder="+94 77 123 4567">
        </div>

        <div class="row">
          <label for="email">Email (optional)</label>
          <input id="email" name="email" type="email" placeholder="you@example.com">
        </div>

        <div class="row-inline">
          <div>
            <label for="date">Date</label>
            <input id="date" name="date" type="date" required value="<?php echo date('Y-m-d'); ?>">
          </div>

          <div>
            <label for="time">Time</label>
            <input id="time" name="time" type="time" required value="<?php echo date('H:i', strtotime('+1 hour')); ?>">
          </div>

          <div>
            <label for="guests">Members</label>
            <input id="guests" name="guests" type="number" min="1" max="20" required value="2">
          </div>
        </div>

        <div class="row-inline">
          <div>
            <label for="meal_type">Prefer Meal Type</label>
            <select id="meal_type" name="meal_type" required>
              <option value="">Select</option>
              <?php foreach($mealTypes as $mt): ?>
                <option value="<?php echo htmlspecialchars($mt); ?>"><?php echo ucfirst($mt); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label for="country">Category</label>
            <select id="country" name="country" required>
              <option value="">Select</option>
              <?php foreach($countries as $c): ?>
                <option value="<?php echo htmlspecialchars($c); ?>"><?php echo ucfirst($c); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row">
          <button type="submit" class="btn submit-btn">Reserve Table</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
