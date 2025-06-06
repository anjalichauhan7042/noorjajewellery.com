<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Noorja Jewelry</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { width: 80%; margin: auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .nav { margin-top: 20px; }
        .nav a { display: inline-block; margin: 10px; padding: 10px 20px; background: #ff8c00; color: white; text-decoration: none; border-radius: 5px; }
        .nav a:hover { background: #ff6a00; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, Admin!</h1>
        <div class="nav">
            <a href="manage_products.php">Manage Products</a>
            <a href="manage_users.php">Manage Users</a>
            <a href="view_orders.php">View Orders</a>
            <a href="admin_feedback.php">View Feedback</a>
            <a href="about_us.php">About Us</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
