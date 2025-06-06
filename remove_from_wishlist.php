<?php
session_start();
include "config.php"; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_GET['id']); // Make sure it's an integer

    // Use prepared statement for safety
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Removed from Wishlist!'); window.location.href='wishlist.php';</script>";
    } else {
        echo "<script>alert('Failed to remove item.'); window.location.href='wishlist.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request!'); window.location.href='wishlist.php';</script>";
}
?>
