<?php
session_start();
require 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $description = $_POST['description'];

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $target_dir = "../assets/images/";
    move_uploaded_file($image_tmp, $target_dir . $image_name);

    $stmt = $conn->prepare("INSERT INTO properties (user_id, title, location, price, bedrooms, bathrooms, description, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdiiss", $user_id, $title, $location, $price, $bedrooms, $bathrooms, $description, $image_name);
    
    if ($stmt->execute()) {
        header("Location: ../views/seller_dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
