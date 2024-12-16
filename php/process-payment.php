<?php
session_start();
require '../php/db.php'; // Include the database connection

// Ensure the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: login.php");
    exit();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user_id = $_SESSION['user_id'];
    $card_number = $_POST['card_number'];
    $name_on_card = $_POST['name_on_card'];
    $expiration_date = $_POST['expiration_date'];
    $cvv = $_POST['cvv'];
    $price = $_POST['price']; // Add a hidden field to pass the price
    $property_id = $_POST['property_id']; // Add a hidden field to pass property details

    // Basic validation for card number, expiration date, and CVV
    if (!preg_match('/^\d{16}$/', $card_number)) {
        $error = "Invalid credit card number. It must be 16 digits.";
    } elseif (!preg_match('/^\d{2}\/\d{2}$/', $expiration_date)) {
        $error = "Invalid expiration date format. Use MM/YY.";
    } elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
        $error = "Invalid CVV. It must be 3 or 4 digits.";
    }

    // If there are validation errors, redirect back to the wishlist page
    if (isset($error)) {
        header("Location: ../views/wishlist.php?error=" . urlencode($error));
        exit();
    }

    // Simulate payment processing (replace this with real payment API integration if required)
    $transaction_status = "success";

    // If payment is successful, save transaction details to the database
    if ($transaction_status === "success") {
        // Insert transaction details
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, property_details, price, card_number, name_on_card, expiration_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $property_id, $price, $card_number, $name_on_card, $expiration_date);

        if ($stmt->execute()) {
            // Remove the purchased item from the wishlist
            $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND property_id = ?");
            if ($stmt) {
                $stmt->bind_param("ii", $user_id, $property_id);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("UPDATE properties SET status = 'Sold' WHERE id = ?");
                    if ($stmt) {
                        $stmt->bind_param("i", $property_id);
                        if ($stmt->execute()) {
                            // Redirect to the buyer dashboard with a success message
                            header("Location: ../views/buyer_dashboard.php?message=" . urlencode("Payment successful! Property status updated to Sold."));
                            exit();
                        } else {
                            $error = "Failed to update property status: " . $stmt->error;
                        }
                    } else {
                        $error = "Failed to prepare property status update query: " . $conn->error;
                    }
                } else {
                    $error = "Failed to remove item from wishlist: " . $stmt->error;
                }
            } else {
                $error = "Failed to prepare wishlist removal query: " . $conn->error;
            }
        } else {
            $error = "Failed to save transaction details: " . $stmt->error;
        }
    } else {
        $error = "Payment failed. Please try again.";
    }

    // If there are errors, redirect back with the error message
    if (isset($error)) {
        header("Location: ../views/wishlist.php?error=" . urlencode($error));
        exit();
    }
} else {
    // If the request method is not POST, redirect back to the wishlist
    header("Location: ../views/wishlist.php");
    exit();
}
