<?php
session_start();
include "config.php"; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from database
$query = "SELECT name, email, role, address, phone FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Fetch user orders from database
$order_query = "SELECT o.id, o.customer_name, 
                       COALESCE(p.name, 'Unknown Product') AS product_name, 
                       o.total_price, o.order_date, o.status 
                FROM orders o 
                LEFT JOIN products p ON o.product_id = p.id 
                WHERE o.customer_name = '{$user['name']}' 
                ORDER BY o.order_date DESC";

$order_result = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Noorja Jewelry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
        }

        /* Profile Container */
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h3 {
            text-align: center;
            color: #0a1128;
        }

        .profile-info {
            margin-top: 20px;
            text-align: center;
        }

        .profile-info p {
            font-size: 18px;
            margin: 10px 0;
        }

        .profile-info strong {
            color: #001f3f;
        }

        /* Update Address Section */
        .update-address {
            margin-top: 20px;
            text-align: center;
        }

        .update-address input {
            width: 90%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .update-address button {
            margin-top: 10px;
            padding: 10px 20px;
            background: #ff4081;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .update-address button:hover {
            background: #e91e63;
        }

        /* Order History Section */
        .order-history {
            margin-top: 30px;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .order-table th, .order-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .order-table th {
            background: #001f3f;
            color: white;
        }

        /* Logout Button */
        .logout {
            text-align: center;
            margin-top: 20px;
        }

        .logout a {
            text-decoration: none;
            background: #0a1128;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .logout a:hover {
            background: #001f3f;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h1>User Profile</h1>

        <div class="profile-info">
            <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
            <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
        </div>

        <!-- Update Address Form -->
        <div class="update-address">
            <h3>Update Address</h3>
            <form action="update_address.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="text" name="new_address" placeholder="Enter new address" required>
                <button type="submit">Update Address</button>
            </form>
        </div>

        <!-- Order History -->
        <div class="order-history">
            <h3>Order History</h3>
            <table class="order-table">
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
                <?php
                if (mysqli_num_rows($order_result) > 0) {
                    while ($order = mysqli_fetch_assoc($order_result)) {
                        echo "<tr>
                                <td>{$order['id']}</td>
                                <td>{$order['product_name']}</td>
                                <td>₹{$order['total_price']}</td>
                                <td>{$order['order_date']}</td>
                                <td>{$order['status']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No orders found.</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Logout Button -->
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>

</body>
</html>
