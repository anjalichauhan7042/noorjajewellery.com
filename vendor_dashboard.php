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
    <title>Vendor Dashboard - Noorja Jewelry</title>
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
                <li><a href="sale_product.php">Sale a Product</a></li>
                <li><a href="sold_products.php">Your Sold Products</a></li> 
                <li><a href="view_feedback.php">View Feedback</a></li>
                <li><a href="about_us.php">About Us</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <span>Welcome, Vendor</span>
                <a href="logout.php">Logout</a>
            </div>

            <div class="section">
                <h2>Dashboard Overview</h2>
                <p>Select an option from the sidebar to manage your products and feedback.</p>
            </div>

        
            </div>
        </div>
    </div>
</body>
</html>
