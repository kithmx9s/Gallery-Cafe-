<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db_connect.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['foodName'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $country = $_POST['country'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($image);

    if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO food_items (name, description, category, country, price, image, availability) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdss", $name, $description, $category, $country, $price, $image, $availability);

        if($stmt->execute()) {
            echo "<script>alert('Food item added successfully!'); window.location='add_food.html';</script>";
        } else {
            echo "<script>alert('Error: Could not add item.'); window.location='add_food.html';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error uploading image.'); window.location='add_food.html';</script>";
    }

    $conn->close();
} else {
    header("Location: add_food.html");
}
?>
