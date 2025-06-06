<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first!'); window.location='login.php';</script>";
    exit();
}

$cart_id = $_GET['id'];
mysqli_query($conn, "DELETE FROM cart WHERE id = '$cart_id'");

echo "<script>alert('Item removed from cart!'); window.location='cart.php';</script>";
?>
