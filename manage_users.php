<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT id, name, email, role FROM users");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: manage_users.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
            text-align: center;
        }
        th {
            background: #ff8c00;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .delete-btn {
            display: inline-block;
            padding: 6px 12px;
            background: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .delete-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>
         <a href="admin_dashboard.php">
            <button class="btn-dashboard">Back to Dashboard</button>
        </a>

        <table>
            <tr><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><a href="?delete=<?= $row['id'] ?>" class="delete-btn">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
