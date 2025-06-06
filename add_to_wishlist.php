<?php
session_start();
include "config.php"; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if product_id is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid request!'); window.location.href='home.php';</script>";
    exit();
}

$product_id = intval($_GET['id']); // Ensure it's an integer

// Use prepared statement to check if product is already in wishlist
$checkQuery = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Product already in wishlist!'); window.location.href='wishlist.php';</script>";
    exit();
}

// Use prepared statement to insert into wishlist
$query = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {
    echo "<script>alert('Added to wishlist!'); window.location.href='wishlist.php';</script>";
} else {
    echo "<script>alert('Error adding to wishlist. Try again.'); window.location.href='home.php';</script>";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>