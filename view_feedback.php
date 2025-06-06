<?php
session_start();
include 'config.php';

// Ensure only a vendor can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: login.php");
    exit();
}

$vendor_id = $_SESSION['user_id']; // Get vendor's ID from session

// Fetch feedback for this vendor
$query = "SELECT users.name AS customer_name, feedback.message, feedback.created_at 
          FROM feedback 
          JOIN users ON feedback.user_id = users.id 
          WHERE feedback.vendor_id = ? 
          ORDER BY feedback.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback - Vendor Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        h2 {
            color: #003366;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #003366;
            color: white;
        }
        .no-feedback {
            text-align: center;
            padding: 20px;
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Customer Feedback</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Customer Name</th>
                <th>Feedback</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-feedback">No feedback received yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
