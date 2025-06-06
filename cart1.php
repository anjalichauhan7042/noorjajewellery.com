<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to proceed to checkout!'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all cart items for the user
$cart_query = mysqli_query($conn, "SELECT cart.quantity, products.name, products.price, products.image 
                                   FROM cart 
                                   JOIN products ON cart.product_id = products.id 
                                   WHERE cart.user_id = '$user_id'");

$total_price = 0;
$total_items = 0;
$cart_items = [];

while ($row = mysqli_fetch_assoc($cart_query)) {
    $item_total = $row['price'] * $row['quantity'];
    $total_price += $item_total;
    $total_items += $row['quantity'];
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Noorja Jewelry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #003366;
            margin-bottom: 20px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #003366;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            text-decoration: none;
        }
        .btn:hover {
            background: #002244;
        }
        .order-summary {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .order-summary h3 {
            color: #003366;
            margin-bottom: 15px;
        }
        .order-summary p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .gift-message a {
            color: #003366;
            text-decoration: none;
            font-weight: bold;
        }
        .gift-message a:hover {
            text-decoration: underline;
        }
        .product-item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Order Confirmation</h2>

    <form method="POST">
        <button type="submit" class="btn">Continue to Delivery</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['total_price'] = $total_price;
        $_SESSION['total_items'] = $total_items;
        header("Location: delivery.php");
        exit();
    }
    ?>
</div>

<!-- Order Summary Section -->
<div class="order-summary">
    <h3>ORDER SUMMARY</h3>
    <p><strong>Total (<?php echo $total_items; ?> Item<?php echo $total_items > 1 ? 's' : ''; ?>):</strong> ₹<?php echo number_format($total_price, 2); ?></p>

    <?php foreach ($cart_items as $item): ?>
        <div class="product-item">
            <p><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['quantity']; ?> - ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
        </div>
    <?php endforeach; ?>

    <p><strong>Total Payable:</strong> ₹<?php echo number_format($total_price, 2); ?></p>

    <div class="gift-message">
        <p><strong>Gift Message (Optional)</strong> <a href="#">Add</a></p>
    </div>
</div>

</body>
</html>
