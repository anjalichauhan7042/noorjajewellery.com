<?php
session_start();
include 'config.php';

// Ensure only a vendor can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: login.php");
    exit();
}

$vendor_id = $_SESSION['user_id']; // Vendor ID from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sold Products - Noorja Jewelry</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            background: #003366;
            color: white;
            width: 250px;
            padding: 20px;
        }
        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            margin-bottom: 15px;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
        }
        .sidebar ul li a:hover {
            color: #ff8c00;
        }
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            background: white;
        }
        .header {
            background: white;
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        .header a {
            text-decoration: none;
            color: #003366;
            font-weight: bold;
            margin-left: 20px;
        }
        .section {
            margin-top: 30px;
        }
        .section h2 {
            margin-bottom: 15px;
            color: #003366;
        }
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #003366;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Vendor Dashboard</h2>
            <ul>
                <li><a href="sale_product.php">Sale Product</a></li>
                <li><a href="view_feedback.php">View Feedback</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="sold_products.php">Sold Products</a></li> <!-- New Link Added -->
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <span>Welcome, Vendor</span>
                <a href="logout.php">Logout</a>
            </div>

            <div class="section">
                <h2>Your Sold Products</h2>

                <?php
                // Fetch sold orders of this vendor
                $sold_query = $conn->prepare("
                    SELECT * FROM orders 
                    WHERE vendor_id = ?
                    ORDER BY order_date DESC
                ");
                $sold_query->bind_param("i", $vendor_id);
                $sold_query->execute();
                $sold_products = $sold_query->get_result();
                ?>

                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Customer Name</th>
                            <th>Total Price (₹)</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($sold_products->num_rows > 0) { 
                            while ($sold = $sold_products->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($sold['product_name']) ?></td>
                                <td><?= htmlspecialchars($sold['customer_name']) ?></td>
                                <td><?= number_format($sold['total_price'], 2) ?></td>
                                <td><?= htmlspecialchars(date('d-m-Y', strtotime($sold['order_date']))) ?></td>
                                <td><?= htmlspecialchars($sold['status']) ?></td>
                                <td><?= htmlspecialchars($sold['payment_method']) ?></td>
                            </tr>
                        <?php }} else { ?>
                            <tr><td colspan="6">No sold products yet.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</body>
</html>
