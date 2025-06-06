<?php
session_start();
include 'config.php';

// Get the latest order
$order_query = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 1";
$result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($result);

// Generate estimated delivery date (5-7 days from order date)
$order_date = strtotime($order['order_date']);
$delivery_date = date('F j, Y', strtotime('+'.rand(5,7).' days', $order_date));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #28a745;
            font-size: 24px;
        }
        .tracking-status {
            text-align: left;
            margin-top: 20px;
        }
        .status-bar {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            position: relative;
        }
        .status-bar div {
            width: 24px;
            height: 24px;
            background: #ddd;
            border-radius: 50%;
            position: relative;
            text-align: center;
            font-size: 14px;
            line-height: 24px;
            font-weight: bold;
        }
        .status-bar div.active {
            background: #28a745;
            color: white;
        }
        .status-line {
            position: absolute;
            top: 10px;
            left: 0;
            width: 100%;
            height: 4px;
            background: #ddd;
            z-index: -1;
        }
        .status-line.progress {
            background: #28a745;
            width: 70%;
        }
        .estimated-delivery {
            margin-top: 20px;
            font-size: 16px;
        }
        .home-btn {
            display: inline-block;
            padding: 12px 20px;
            background: black;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }
        .home-btn:hover {
            background: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Order Placed Successfully! 🎉</h2>

    <div class="tracking-status">
        <p><strong>Order Tracking Status:</strong></p>
        <div class="status-bar">
            <div class="active">✔</div>
            <div>📦</div>
            <div >📦</div>
            <div>📦</div>
            <div class="status-line progress"></div>
        </div>
        <p>Processing → Shipped → Out for Delivery → Delivered</p>
    </div>

    <div class="estimated-delivery">
        <p><strong>Estimated Delivery:</strong> <?php echo $delivery_date; ?></p>
    </div>

    <a href="home.php" class="home-btn">Go to Home</a>
</div>

</body>
</html>
