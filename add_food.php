<?php

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

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2><span><i class="fas fa-coffee"></i></span> Café Legend</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="admin_dashboard.html"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="active"><a href="add_food.html"><i class="fas fa-utensils"></i> Add Food</a></li>
            <li><a href="#"><i class="fas fa-table"></i> Manage Menu</a></li>
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
            <form action="add_food_process.php" method="POST" enctype="multipart/form-data">
                
                <label for="foodName">Food Name</label>
                <input type="text" id="foodName" name="foodName" placeholder="Enter food name" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter description" required></textarea>

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
                <input type="number" id="price" name="price" placeholder="Enter price" required>

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

    <script src="add_food.js"></script>
</body>
</html>
