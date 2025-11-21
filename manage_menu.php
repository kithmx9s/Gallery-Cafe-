<?php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
    <link rel="stylesheet" href="css/manage_menu.css">
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
            <li><a href="add_food.html"><i class="fas fa-utensils"></i> Add Food</a></li>
            <li class="active"><a href="#"><i class="fas fa-table"></i> Manage Menu</a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> Reservations</a></li>
            <li><a href="#"><i class="fas fa-bullhorn"></i> Events</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <h2>Manage Menu</h2>
        </header>

        <section class="table-section">
            <table class="menu-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Country</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM food_items ORDER BY created_at DESC");
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            echo "<tr>
                                    <td><img src='images/".$row['image']."' class='item-img'></td>
                                    <td>".$row['name']."</td>
                                    <td>".$row['category']."</td>
                                    <td>".$row['country']."</td>
                                    <td>LKR ".$row['price']."</td>
                                    <td>".$row['availability']."</td>
                                    <td><a href='edit_food.php?id=".$row['id']."' class='edit-btn'><i class='fas fa-edit'></i></a></td>
                                    <td><a href='delete_food.php?id=".$row['id']."' class='delete-btn'><i class='fas fa-trash'></i></a></td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No food items found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <script src="manage_menu.js"></script>
</body>
</html>
