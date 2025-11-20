<?php
include 'db_connect.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Get image file to delete
    $res = $conn->query("SELECT image FROM food_items WHERE id='$id'");
    $row = $res->fetch_assoc();
    $imgPath = "images/".$row['image'];
    if(file_exists($imgPath)){
        unlink($imgPath);
    }

    // Delete from database
    $conn->query("DELETE FROM food_items WHERE id='$id'");
    header("Location: manage_menu.php");
}
?>
