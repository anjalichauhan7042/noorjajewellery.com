<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details
$user_name = $_SESSION['user_name'];

// Check if product ID and price are received
if (!isset($_POST['product_id']) || !isset($_POST['price'])) {
    echo "Invalid request.";
    exit();
}

$product_id = intval($_POST['product_id']);
$total_price = floatval($_POST['price']);
$order_date = date('Y-m-d H:i:s');
$status = 'Pending';

// Fetch product details
$query = "SELECT name FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}

$product_name = $product['name'];

// Insert order into database
$insert_query = "INSERT INTO orders (customer_name, product_name, total_price, order_date, status) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("ssdss", $user_name, $product_name, $total_price, $order_date, $status);

if ($stmt->execute()) {
    header("Location: order_success.php");
    exit();
} else {
    echo "❌ Error placing order!";
}
?>
