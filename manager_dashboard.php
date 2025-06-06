<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$manager_name = $_SESSION['name'] ?? "Manager";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Dashboard - Noorja Jewelry</title>
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
    /* Sidebar styles */
    .sidebar {
      background: #003366;
      color: #fff;
      width: 250px;
      padding: 20px;
    }
    .sidebar h2 {
      margin-bottom: 20px;
      font-size: 22px;
    }
    .sidebar ul {
      list-style: none;
    }
    .sidebar ul li {
      margin-bottom: 15px;
    }
    .sidebar ul li a {
      text-decoration: none;
      color: #fff;
      font-size: 16px;
    }
    .sidebar ul li a:hover {
      color: #ff8c00;
    }
    /* Main content styles */
    .main-content {
      flex: 1;
      padding: 20px;
      background: #fff;
    }
    .header {
      background: #fff;
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
  </style>
</head>
<body>
  <div class="dashboard-container">
    <div class="sidebar">
      <h2>Manager Dashboard</h2>
      <ul>
        <li><a href="add_category.php">Add Category</a></li>
        <li><a href="add_product.php">Add Product</a></li>
        <li><a href="manage_products1.php">Manage Products</a></li>
        <li><a href="manage_orders.php">Manage Orders</a></li>
        <li><a href="change_password.php">Change Password</a></li>
        <li><a href="send_notifications.php">Send Notifications</a></li>
        <li><a href="offers.php">Manage Offers</a></li>
        <li><a href="returns.php">Return & Exchange</a></li>
        <li><a href="about_us.php">About Us</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div>
    <div class="main-content">
      <div class="header">
        <span>Welcome, <?php echo htmlspecialchars($manager_name); ?></span>
        <a href="logout.php">Logout</a>
      </div>
      <div class="section">
        <h2>Dashboard Overview</h2>
        <p>Select an option from the sidebar to manage categories, products, orders, and more.</p>
      </div>
      <!-- Additional content or dashboard summaries can be added here -->
    </div>
  </div>
</body>
</html> 