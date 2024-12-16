<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'seller') {
                header("Location: ../views/seller_dashboard.php");
            } elseif ($user['role'] == 'buyer') {
                header("Location: ../views/buyer_dashboard.php");
            } else {
                header("Location: ../views/admin_dashboard.php");
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
}
?>
