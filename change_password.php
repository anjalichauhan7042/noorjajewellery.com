<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$manager_id = $_SESSION['user_id'];
$message = "";

// Handle password change request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password from database
    $query = "SELECT password FROM users WHERE id = '$manager_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        if (!password_verify($old_password, $row['password'])) {
            $message = "❌ Old password is incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $message = "❌ New passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $message = "❌ Password must be at least 6 characters long.";
        } else {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_query = "UPDATE users SET password = '$hashed_password' WHERE id = '$manager_id'";
            
            if (mysqli_query($conn, $update_query)) {
                $message = "✅ Password changed successfully!";
            } else {
                $message = "❌ Error updating password: " . mysqli_error($conn);
            }
        }
    } else {
        $message = "❌ User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 40%; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h2 { text-align: center; color: #003366; }
        label { font-weight: bold; display: block; margin: 10px 0 5px; }
        input { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #003366; color: white; padding: 10px; border: none; width: 100%; cursor: pointer; }
        .btn:hover { background: #ff8c00; }
        .message { text-align: center; margin-top: 10px; color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
        <form method="POST">
            <label>Old Password</label>
            <input type="password" name="old_password" required>

            <label>New Password</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" class="btn">Update Password</button>
        </form>
<a href="manager_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>


    </div>
</body>
</html>
