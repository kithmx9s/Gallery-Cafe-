<?php
// ---------------------------------------------
// PROCESS FORM SUBMISSION
// ---------------------------------------------
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db_connect.php';

$message = ""; // for alert message

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name         = $_POST['foodName'];
    $description  = $_POST['description'];
    $category     = $_POST['category'];
    $country      = $_POST['country'];
    $price        = $_POST['price'];
    $availability = $_POST['availability'];

    // Handle image
    $image = $_FILES['image']['name'];
    $target_dir  = "images/";
    $target_file = $target_dir . basename($image);

    if(!is_dir("images")){
        mkdir("images");
    }

    if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {

        $stmt = $conn->prepare("
            INSERT INTO food_items 
            (name, description, category, country, price, image, availability)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("ssssdss",
            $name, $description, $category, $country, $price, $image, $availability
        );

        if($stmt->execute()) {
            $message = "Food item added successfully!";
        } else {
            $message = "Database error: Could not insert record.";
        }

    } else {
        $message = "Error uploading image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Item</title>
    <link rel="stylesheet" href="css/add_food.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>

<?php if($message != ""): ?>
<script>
    alert("<?php echo $message; ?>");
</script>
<?php endif; ?>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <h2><span><i class="fas fa-coffee"></i></span> Café Legend</h2>
    </div>
    <ul class="sidebar-menu">
        <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="active"><a href="add_food.php"><i class="fas fa-utensils"></i> Add Food</a></li>
        <li><a href="manage_menu.php"><i class="fas fa-table"></i> Manage Menu</a></li>
        <li><a href="#"><i class="fas fa-calendar-alt"></i> Reservations</a></li>
        <li><a href="#"><i class="fas fa-bullhorn"></i> Events</a></li>
        <li><a href="#"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="#"><i class="fas fa-chart-line"></i> Analytics</a></li>
        <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<!-- Main Content -->
<main class="main-content">
    <header>
        <h2>Add Food Item</h2>
    </header>

    <section class="add-food-form">
        <form action="add_food.php" method="POST" enctype="multipart/form-data">

            <label for="foodName">Food Name</label>
            <input type="text" id="foodName" name="foodName" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>

            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select category</option>
                <option value="main">Main</option>
                <option value="beverage">Beverage</option>
                <option value="dessert">Dessert</option>
            </select>

            <label for="country">Country</label>
            <select id="country" name="country" required>
                <option value="">Select country</option>
                <option value="srilankan">Sri Lankan</option>
                <option value="indian">Indian</option>
                <option value="chinese">Chinese</option>
                <option value="arabian">Arabian</option>
                <option value="mongolian">Mongolian</option>
                <option value="french">French Bakery</option>
                <option value="italian">Italian Bakery</option>
            </select>

            <label for="price">Price (LKR)</label>
            <input type="number" id="price" name="price" required>

            <label for="image">Upload Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <label for="availability">Availability</label>
            <select id="availability" name="availability" required>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>

            <button type="submit" class="submit-btn">Add Food Item</button>
        </form>
    </section>
</main>

</body>
</html>
