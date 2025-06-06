<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first!'); window.location='login.php';</script>";
    exit();
}

if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    if ($quantity > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity='$quantity' WHERE id='$cart_id'");
    } else {
        mysqli_query($conn, "DELETE FROM cart WHERE id='$cart_id'"); // Remove item if quantity is zero
    }
}

echo "<script>window.location='cart.php';</script>";
?>
