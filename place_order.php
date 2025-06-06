<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user ID from session
    $user_id = $_SESSION['user_id'];
    
    // Get product details (assuming product_id and total_price are passed via POST or retrieved from cart)
    $product_id = $_POST['product_id']; // or fetch from the cart session
    $total_price = $_POST['total_price']; // or calculate from cart session

    // Set the order date and default status
    $order_date = date("Y-m-d H:i:s");
    $status = "Pending"; // Default status

    // Insert the order into the database
    $query = "INSERT INTO orders (user_id, product_id, total_price, order_date, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iidss", $user_id, $product_id, $total_price, $order_date, $status);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Order placed successfully!'); window.location.href='order_confirmation.php';</script>";
    } else {
        echo "<script>alert('❌ Error placing order!'); window.history.back();</script>";
    }
}
?>
