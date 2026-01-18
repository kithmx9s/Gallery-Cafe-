<?php
session_start();
include 'db_connect.php';

define('DAILY_SEAT_LIMIT', 100);

$flash_message = $_SESSION['flash_message'] ?? null;
$flash_type    = $_SESSION['flash_type']    ?? null;
$flash_ref     = $_SESSION['flash_ref']     ?? null;

unset($_SESSION['flash_message']);
unset($_SESSION['flash_type']);
unset($_SESSION['flash_ref']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']      ?? '');
    $email    = trim($_POST['email']     ?? '');
    $phone    = trim($_POST['telephone'] ?? '');
    $date     = $_POST['date']           ?? '';
    $time     = $_POST['time']           ?? '';
    $members  = (int)($_POST['members']  ?? 0);

    $error = '';

    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || $members < 1) {
        $error = "Please fill all required fields correctly.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($members > 20) {
        $error = "Maximum 20 people per reservation.";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
        $error = "Invalid date format.";
    }

    if ($error) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['flash_message'] = $error;
        $_SESSION['flash_type']    = 'error';
        header("Location: reservation.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT COALESCE(SUM(party_size), 0) AS total FROM reservations WHERE res_date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $already_reserved = (int)$row['total'];
    $available = DAILY_SEAT_LIMIT - $already_reserved;
    $stmt->close();

    if ($members > $available) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['flash_message'] = "Sorry, only $available seat(s) available for " . date('d M Y', strtotime($date)) . ".";
        $_SESSION['flash_type']    = 'error';
        header("Location: reservation.php");
        exit;
    }

    $stmt_count = $conn->prepare("SELECT COUNT(*) AS cnt FROM reservations WHERE res_date = ?");
    $stmt_count->bind_param("s", $date);
    $stmt_count->execute();
    $res_count = $stmt_count->get_result();
    $row_count = $res_count->fetch_assoc();
    $next_seq = (int)$row_count['cnt'] + 1;
    $reference = date('Ymd', strtotime($date)) . str_pad($next_seq, 3, '0', STR_PAD_LEFT);
    $stmt_count->close();

    $stmt_insert = $conn->prepare("
        INSERT INTO reservations 
        (ref_number, res_date, res_time, customer_name, email, phone, party_size)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt_insert->bind_param("ssssssi", $reference, $date, $time, $name, $email, $phone, $members);

    if ($stmt_insert->execute()) {
        $_SESSION['flash_message'] = "Your reservation has been confirmed!";
        $_SESSION['flash_type']    = 'success';
        $_SESSION['flash_ref']     = $reference;
        header("Location: reservation.php?success=1");
        exit;
    } else {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['flash_message'] = "Database error: " . $stmt_insert->error;
        $_SESSION['flash_type']    = 'error';
        header("Location: reservation.php");
        exit;
    }

    $stmt_insert->close();
}

$today = date('Y-m-d');
$stmt_today = $conn->prepare("SELECT COALESCE(SUM(party_size), 0) AS total FROM reservations WHERE res_date = ?");
$stmt_today->bind_param("s", $today);
$stmt_today->execute();
$res_today = $stmt_today->get_result();
$row_today = $res_today->fetch_assoc();
$today_reserved = (int)$row_today['total'];
$today_available = DAILY_SEAT_LIMIT - $today_reserved;
$stmt_today->close();

$conn->close();

$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation - Gallery Café</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/reservation_style.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reservation_model.css">
</head>
<body>
<!-- ===== NAVBAR ===== -->
  <header>
    <nav class="navbar">
      <!-- Left: Logo -->
      <div class="nav-logo">
        <img src="assets/logo.png" alt="Gallery Café Logo" />
        <span>Gallery Café</span>
      </div>

      <!-- Center: Nav Links -->
        <ul class="nav-links">
          <li><a href="index.html" class="active">Home</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="reservation.php">Reservation</a></li>
          <li><a href="#events">Events</a></li>
          <li><a href="about.html">About Us</a></li>
        </ul>

      <!-- Right: Icons -->
      <div class="nav-icons">
        <a href="#login" class="login-btn"><i class="fa-solid fa-user"></i></a>
        <a href="#search" class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></a>
      </div>

      <!-- Mobile Menu Button -->
      <div class="menu-toggle" id="menu-toggle">
        <i class="fa-solid fa-bars"></i>
      </div>
    </nav>
  </header>


    <div class="container">
        <div class="header">
            <h1>Make a Reservation</h1>
            <div class="seats-info">
                <div class="seat-stat">
                    <span class="label">Total Seats</span>
                    <span class="value"><?= DAILY_SEAT_LIMIT ?></span>
                </div>
                <div class="seat-stat">
                    <span class="label">Reserved Today</span>
                    <span class="value"><?= $today_reserved ?></span>
                </div>
                <div class="seat-stat">
                    <span class="label">Available Today</span>
                    <span class="value highlight"><?= $today_available ?></span>
                </div>
            </div>
        </div>

        <?php if ($flash_message && $flash_type === 'error'): ?>
        <div class="message error">
            <?= htmlspecialchars($flash_message) ?>
        </div>
        <?php endif; ?>

        <form id="reservationForm" class="reservation-form" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($form_data['name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($form_data['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="telephone">Telephone Number</label>
                <input type="tel" id="telephone" name="telephone" required value="<?= htmlspecialchars($form_data['telephone'] ?? '') ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($form_data['date'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="time" id="time" name="time" required value="<?= htmlspecialchars($form_data['time'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="members">Number of Members</label>
                <input type="number" id="members" name="members" min="1" max="20" required value="<?= htmlspecialchars($form_data['members'] ?? '1') ?>">
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <span>Reserve Now</span>
            </button>
        </form>
    </div>

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
</footer>


    <div id="successModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Reservation Confirmed! 🎉</h2>
            <p style="font-size:1.1rem; color:#333; margin-bottom:20px;">
                <?= htmlspecialchars($flash_message ?? '') ?>
            </p>
            <?php if ($flash_ref): ?>
            <div class="modal-ref"><?= htmlspecialchars($flash_ref) ?></div>
            <?php endif; ?>
            <p style="color:#555; margin:20px 0 30px;">
                Please save or screenshot your reference number.
            </p>
            <button class="modal-btn" onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }

        <?php if ($flash_type === 'success' && $flash_ref): ?>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('successModal').style.display = 'flex';
        });
        <?php endif; ?>
    </script>

    <script src="js/reservation.js"></script>
</body>
</html>