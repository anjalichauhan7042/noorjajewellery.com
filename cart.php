<?php
include "config.php"; // Database connection
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to view the cart!'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the user
$cart_query = mysqli_query($conn, "SELECT cart.id AS cart_id, cart.quantity, products.name, products.price, products.image 
                                   FROM cart 
                                   JOIN products ON cart.product_id = products.id 
                                   WHERE cart.user_id = '$user_id'");

$total_price = 0; // Initialize total price
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
     /* Cart Page Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.cart-container {
    width: 80%;
    margin: 30px auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

h2 {
    color: #333;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f4f4f4;
}

.cart-img {
    width: 60px;
    height: auto;
    border-radius: 5px;
}

/* Buttons */
.update-btn, .remove-btn, .checkout-btn, .home-btn {
    padding: 8px 12px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
}

.update-btn {
    background-color: #007bff;
    color: white;
}

.update-btn:hover {
    background-color: #0056b3;
}

.remove-btn {
    background-color: red;
    color: white;
    text-decoration: none;
    padding: 5px 10px;
    display: inline-block;
}

.remove-btn:hover {
    background-color: darkred;
}

.cart-summary {
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
}

.checkout-btn {
    background-color: #ff6600;
    color: #fff;
    padding: 10px 20px;
}

.checkout-btn:hover {
    background-color: #e65c00;
}

.home-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
}

.home-btn:hover {
    background-color: #0056b3;
}

</style>
</head>
<body>

<div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <table>
        <tr>
            <th>Image</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Remove</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($cart_query)) { 
            $item_total = $row['price'] * $row['quantity'];
            $total_price += $item_total; // Add to subtotal
        ?>
            <tr>
                <td><img src="<?php echo $row['image']; ?>" class="cart-img"></td>
                <td><?php echo $row['name']; ?></td>
                <td>₹<?php echo number_format($row['price'], 2); ?></td>
                <td>
                    <form method="post" action="update_cart.php">
                        <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                        <button type="submit" class="update-btn">Update</button>
                    </form>
                </td>
                <td>₹<?php echo number_format($item_total, 2); ?></td>
                <td><a href="remove_from_cart.php?id=<?php echo $row['cart_id']; ?>" class="remove-btn">Remove</a></td>
            </tr>
        <?php } ?>
    </table>

    <div class="cart-summary">
        <h3>Subtotal: ₹<?php echo number_format($total_price, 2); ?></h3>
        <a href="cart1.php"><button class="checkout-btn">Proceed to Checkout</button></a>
        <a href="home.php"><button class="home-btn">Continue Shopping</button></a>
    </div>
</div>

</body>
</html>
