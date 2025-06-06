<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Fetch orders from the database
$query = "SELECT * FROM orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

// Handle order status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    $update_query = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Order status updated successfully!'); window.location.href='manage_orders.php';</script>";
    } else {
        echo "<script>alert('Error updating order status: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: #fff; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #003366; color: #fff; }
        .update-btn { background: #ff8c00; color: white; padding: 5px 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Orders</h2>
        <?php if (mysqli_num_rows($result) > 0) { ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo number_format($row['total_price'], 2); ?></td>
                <td><?php echo $row['order_date']; ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="order_status">
                            <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Processing" <?php if ($row['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                            <option value="Shipped" <?php if ($row['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="Delivered" <?php if ($row['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                            <option value="Cancelled" <?php if ($row['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="update-btn">Update</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
        <?php } else { ?>
            <p>No orders found.</p>
        <?php } ?>
      <a href="manager_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>


    </div>
</body>
</html>
