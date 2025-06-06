<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Handle status update
if (isset($_POST['update_status'])) {
    $return_id = $_POST['return_id'];
    $new_status = $_POST['status'];
    $update_query = "UPDATE returns SET status='$new_status' WHERE id=$return_id";
    if (mysqli_query($conn, $update_query)) {
        $message = "✅ Return request updated successfully!";
    } else {
        $message = "❌ Error updating request: " . mysqli_error($conn);
    }
}

// Handle request deletion
if (isset($_GET['delete'])) {
    $return_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM returns WHERE id=$return_id");
    header("Location: returns.php");
    exit();
}

// Fetch all return requests
$requests = mysqli_query($conn, "SELECT returns.*, users.name AS customer_name, orders.id AS order_no 
                                 FROM returns 
                                 JOIN users ON returns.user_id = users.id 
                                 JOIN orders ON returns.order_id = orders.id 
                                 ORDER BY request_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Returns</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 80%; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h2 { text-align: center; color: #003366; }
        .message { text-align: center; margin-top: 10px; color: red; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #003366; color: white; }
        select, button { padding: 5px; border-radius: 4px; }
        .btn { background: #003366; color: white; cursor: pointer; padding: 5px 10px; border: none; }
        .btn:hover { background: #ff8c00; }
        .delete-btn { color: red; text-decoration: none; font-weight: bold; }
        .delete-btn:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Returns & Exchanges</h2>
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
        <table>
            <tr>
                <th>Order No.</th>
                <th>Customer</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Request Date</th>
                <th>Action</th>
            </tr>
            <?php while ($request = mysqli_fetch_assoc($requests)) { ?>
                <tr>
                    <td><?php echo $request['order_no']; ?></td>
                    <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['reason']); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="return_id" value="<?php echo $request['id']; ?>">
                            <select name="status">
                                <option value="Pending" <?php if ($request['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Approved" <?php if ($request['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                                <option value="Rejected" <?php if ($request['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                            </select>
                            <button type="submit" name="update_status" class="btn">Update</button>
                        </form>
                    </td>
                    <td><?php echo $request['request_date']; ?></td>
                    <td>
                        <a href="returns.php?delete=<?php echo $request['id']; ?>" class="delete-btn">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
<a href="manager_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>


    </div>
</body>
</html>
