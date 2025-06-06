<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Delete product logic
$deleteMessage = "";
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $deleteMessage = "Product deleted successfully!";
    } else {
        $deleteMessage = "Error deleting product.";
    }
}

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Admin</title>
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
        button {
            padding: 10px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-delete {
            background: red;
            color: white;
        }
        .btn-dashboard {
            background: #007bff;
            color: white;
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
            color: green;
            margin-bottom: 10px;
        }
        #confirmBox {
            display: none;
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #ccc;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            z-index: 9999;
        }
        #confirmBox button {
            margin: 10px;
        }
    </style>
    <script>
        function confirmDelete(id) {
            document.getElementById('confirmBox').style.display = 'block';
            document.getElementById('confirmDeleteBtn').onclick = function () {
                window.location.href = 'manage_products.php?delete=' + id;
            };
            document.getElementById('cancelDeleteBtn').onclick = function () {
                document.getElementById('confirmBox').style.display = 'none';
            };
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Manage Products</h2>

        <?php if ($deleteMessage): ?>
            <p class="message"><?= htmlspecialchars($deleteMessage) ?></p>
        <?php endif; ?>

        <a href="admin_dashboard.php">
            <button class="btn-dashboard">Back to Dashboard</button>
        </a>

        <table>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?></td>
                    <td>
                        <button class="btn-delete" onclick="confirmDelete(<?= $row['id'] ?>)">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div id="confirmBox">
            <p>Are you sure you want to delete this product?</p>
            <button id="confirmDeleteBtn" style="background: red; color: white;">Yes</button>
            <button id="cancelDeleteBtn" style="background: gray; color: white;">Cancel</button>
        </div>
    </div>
</body>
</html>
