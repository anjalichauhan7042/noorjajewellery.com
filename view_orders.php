<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT orders.id, users.name, orders.product_name, orders.total_price, orders.status 
                        FROM orders 
                        JOIN users ON orders.customer_name = users.name");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #ff8c00;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order Management</h2>
          <a href="admin_dashboard.php">
            <button class="btn-dashboard">Back to Dashboard</button>
        </a>
<table>
    <tr><th>Order ID</th><th>Customer Name</th><th>Product Name</th><th>Total Price</th><th>Status</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td>₹<?= htmlspecialchars($row['total_price']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
        </tr>
    <?php endwhile; ?>
</table>


    </div>
</body>
</html>
