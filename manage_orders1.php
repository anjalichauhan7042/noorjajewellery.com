<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Fetch orders
$orders_query = "SELECT o.*, u.name AS customer_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC";
$orders_result = mysqli_query($conn, $orders_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ccc; }
        th { background: #003366; color: white; }
        .actions select, .actions button { padding: 5px; }
    </style>
</head>
<body>

<h2>Manage Orders</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($order = mysqli_fetch_assoc($orders_result)) { ?>
    <tr>
        <td><?php echo $order['id']; ?></td>
        <td><?php echo $order['customer_name']; ?></td>
        <td><?php echo $order['status']; ?></td>
        <td class="actions">
            <form method="POST" action="update_order.php">
                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                <select name="status">
                    <option>Pending</option>
                    <option>Shipped</option>
                    <option>Delivered</option>
                </select>
                <button type="submit">Update</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<a href="manager_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>



</body>
</html>
