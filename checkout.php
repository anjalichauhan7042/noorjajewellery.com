<?php
session_start();
include 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details
$user_id = $_SESSION['user_id'];
$user_query = "SELECT name FROM users WHERE id = ?";
$stmt_user = $conn->prepare($user_query);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$customer_name = $user['name'];

// Get product details
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid product.";
    exit();
}

$product_id = intval($_GET['id']);
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Noorja Jewelry</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #003366; margin-bottom: 20px; }
        label { font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-submit { background: #003366; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; margin-top: 15px; width: 100%; }
        .btn-submit:hover { background: #002244; }
    </style>
</head>
<body>

<div class="container">
    <h2>Checkout</h2>
    
    <p><strong>Product:</strong> <?php echo htmlspecialchars($product['name']); ?></p>
    <p><strong>Price:</strong> ₹<?php echo number_format($product['price'], 2); ?></p>
    
    <form action="place_order.php" method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>">
        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
        <input type="hidden" name="total_price" value="<?php echo $product['price']; ?>">

        <label for="payment">Payment Method:</label>
        <select name="payment_method" required>
            <option value="cod">Cash on Delivery</option>
            <option value="upi">UPI Payment</option>
            <option value="card">Credit/Debit Card</option>
        </select>

        <button type="submit" class="btn-submit">Place Order</button>
    </form>
</div>

</body>
</html>
