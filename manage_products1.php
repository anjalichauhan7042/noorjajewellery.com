<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Fetch products with category
$products_query = "SELECT p.id, p.name, p.type, p.gender, p.price, p.image, c.name AS category_name 
                   FROM products p 
                   LEFT JOIN categories c ON p.category_id = c.id";

$products_result = mysqli_query($conn, $products_query);
?>
<?php if (isset($_SESSION['message'])): ?>
    <div class="message" style="background:#e7f3fe;padding:10px;border-left:5px solid #003366;margin-bottom:15px;color:#003366;">
        <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']); 
        ?>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #003366; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #003366; color: white; }
        tr:hover { background-color: #f1f1f1; }
        img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        .actions { text-align: center; }
        .actions a { text-decoration: none; font-weight: bold; padding: 6px 10px; border-radius: 5px; display: inline-block; }
        .edit-btn { background: #28a745; color: white; }
        .edit-btn:hover { background: #218838; }
        .delete-btn { background: #dc3545; color: white; }
        .delete-btn:hover { background: #c82333; }
        .add-product { display: block; width: max-content; margin: 10px auto 20px; padding: 10px 15px; background: #003366; color: white; text-decoration: none; font-weight: bold; border-radius: 5px; text-align: center; }
        .add-product:hover { background: #002244; }
        .back-btn { display: block; width: max-content; margin: 30px auto 0; padding: 10px 15px; background: #555; color: white; text-decoration: none; font-weight: bold; border-radius: 5px; }
        .back-btn:hover { background: #333; }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr { display: block; }
            thead tr { display: none; }
            tr { margin-bottom: 10px; border-bottom: 2px solid #ddd; }
            td { display: flex; justify-content: space-between; padding: 10px; }
            td::before { font-weight: bold; color: #003366; }
            td:nth-of-type(1)::before { content: "ID"; }
            td:nth-of-type(2)::before { content: "Name"; }
            td:nth-of-type(3)::before { content: "Category"; }
            td:nth-of-type(4)::before { content: "Type"; }
            td:nth-of-type(5)::before { content: "Gender"; }
            td:nth-of-type(6)::before { content: "Price"; }
            td:nth-of-type(7)::before { content: "Image"; }
            td:nth-of-type(8)::before { content: "Actions"; }
        }
    </style>
    <script>
        function confirmDelete(productName, productId) {
            return confirm("Are you sure you want to delete '" + productName + "'?");
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Manage Products</h2>
    
    <a href="add_product.php" class="add-product">➕ Add New Product</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Type</th>
                <th>Gender</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = mysqli_fetch_assoc($products_result)) { ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                <td><?php echo htmlspecialchars($product['type']); ?></td>
                <td><?php echo htmlspecialchars($product['gender']); ?></td>
                <td>₹<?php echo number_format($product['price'], 2); ?></td>
                <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image"></td>
                <td class="actions">
                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="edit-btn">✏️ Edit</a>
                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="delete-btn" onclick="return confirmDelete('<?php echo addslashes($product['name']); ?>', <?php echo $product['id']; ?>)">🗑 Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="manager_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>
