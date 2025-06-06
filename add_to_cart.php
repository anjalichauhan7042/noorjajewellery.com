<?php
include "config.php"; // Database connection
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to add items to the cart!'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'];

// Check if the product is already in the cart
$check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id='$user_id' AND product_id='$product_id'");
} else {
    mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)");
}

echo "<script>alert('Product added to cart!'); window.location='cart.php';</script>";
?>
