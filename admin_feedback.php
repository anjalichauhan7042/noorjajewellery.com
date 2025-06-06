<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT feedback.id, users.name, feedback.message, feedback.created_at 
                        FROM feedback 
                        JOIN users ON feedback.user_id = users.id 
                        ORDER BY feedback.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #ff8c00;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .message {
            max-width: 400px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Customer Feedback</h2>
          <a href="admin_dashboard.php">
            <button class="btn-dashboard">Back to Dashboard</button>
        </a>

        <table>
            <tr><th>User</th><th>Message</th><th>Date</th></tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td class="message"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
