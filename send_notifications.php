<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $notification_message = mysqli_real_escape_string($conn, $_POST['message']);
    $recipient_id = $_POST['user_id'];  // Customer ID

    if (!empty($notification_message) && !empty($recipient_id)) {
        $query = "INSERT INTO notifications (user_id, message, status) VALUES ('$recipient_id', '$notification_message', 'unread')";
        if (mysqli_query($conn, $query)) {
            $message = "✅ Notification sent successfully!";
        } else {
            $message = "❌ Error sending notification: " . mysqli_error($conn);
        }
    } else {
        $message = "❌ Please enter a message and select a recipient.";
    }
}

// Fetch all customers to show in the dropdown
$customers = mysqli_query($conn, "SELECT id, name FROM users WHERE role = 'customer'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notifications</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 40%; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h2 { text-align: center; color: #003366; }
        label { font-weight: bold; display: block; margin: 10px 0 5px; }
        textarea, select, .btn { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #003366; color: white; cursor: pointer; }
        .btn:hover { background: #ff8c00; }
        .message { text-align: center; margin-top: 10px; color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Send Notification</h2>
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
        <form method="POST">
            <label>Select Customer</label>
            <select name="user_id" required>
                <option value="">-- Select Customer --</option>
                <?php while ($row = mysqli_fetch_assoc($customers)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php } ?>
            </select>

            <label>Notification Message</label>
            <textarea name="message" rows="4" required></textarea>

            <button type="submit" class="btn">Send Notification</button>
        </form>
<a href="manager_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>


    </div>
</body>
</html>
