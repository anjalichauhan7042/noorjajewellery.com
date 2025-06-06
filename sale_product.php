<?php
session_start();
include 'config.php';

// Ensure only vendors can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: login.php");
    exit();
}

$vendor_id = $_SESSION['user_id'];
$message = "";

// Handle product insert
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $gender = $_POST['gender'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = "images/products/" . $image_name;

    if ($image_name) {
        move_uploaded_file($image_tmp_name, $image_folder);

        $stmt = $conn->prepare("INSERT INTO products (vendor_id, name, category, type, gender, price, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssdss", $vendor_id, $name, $category, $type, $gender, $price, $description, $image_folder);

        if ($stmt->execute()) {
            $message = "Product added successfully!";
        } else {
            $message = "Error adding product.";
        }
    } else {
        $message = "Please upload an image.";
    }
}

// Handle product update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $gender = $_POST['gender'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = "images/products/" . $image_name;

    if ($image_name) {
        move_uploaded_file($image_tmp_name, $image_folder);
        $stmt = $conn->prepare("UPDATE products SET name=?, category=?, type=?, gender=?, price=?, description=?, image=? WHERE id=? AND vendor_id=?");
        $stmt->bind_param("sssssdsii", $name, $category, $type, $gender, $price, $description, $image_folder, $product_id, $vendor_id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, category=?, type=?, gender=?, price=?, description=? WHERE id=? AND vendor_id=?");
        $stmt->bind_param("ssssdsii", $name, $category, $type, $gender, $price, $description, $product_id, $vendor_id);
    }

    if ($stmt->execute()) {
        $message = "Product updated successfully!";
    } else {
        $message = "Error updating product.";
    }
}

// Fetch vendor's products
$query = "SELECT * FROM products WHERE vendor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$products_result = $stmt->get_result();

// If update request (click on Update button)
$update_product = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND vendor_id = ?");
    $edit_stmt->bind_param("ii", $edit_id, $vendor_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    if ($edit_result->num_rows > 0) {
        $update_product = $edit_result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Sale Product</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 1200px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin: auto; }
        h2 { text-align: center; color: #003366; }
        form { margin-top: 30px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="number"], select, textarea {
            width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;
        }
        button { margin-top: 15px; padding: 10px 20px; background: #003366; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0055a5; }
        .message { text-align: center; color: green; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 12px; text-align: center; }
        th { background: #003366; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        tr:hover { background: #ddd; }
         form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form label {
    font-weight: bold;
}

form input[type="text"],
form input[type="number"],
form input[type="file"],
form select,
form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

form button {
    width: 200px;
    align-self: center;
    background-color: #003366;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

form button:hover {
    background-color: #0055a5;
}

    </style>
</head>
<body>

<div class="container">
    <h2><?= $update_product ? "Update Product" : "Add New Product" ?></h2>
      <a href="vendor_dashboard.php">
            <button class="btn-dashboard">Back to Dashboard</button>
        </a>


    <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

    <!-- Add / Update Product Form -->
    <form method="POST" enctype="multipart/form-data">
        <?php if ($update_product): ?>
            <input type="hidden" name="product_id" value="<?= $update_product['id'] ?>">
        <?php endif; ?>

        <label>Product Name:</label>
        <input type="text" name="name" value="<?= $update_product ? htmlspecialchars($update_product['name']) : '' ?>" required>

        <label>Category:</label>
        <select name="category" required>
            <option value="Handmade" <?= $update_product && $update_product['category'] == 'Handmade' ? 'selected' : '' ?>>Handmade</option>
        </select>

        <label>Type:</label>
        <select name="type" required>
            <option value="Earrings" <?= $update_product && $update_product['type'] == 'Earrings' ? 'selected' : '' ?>>Earrings</option>
            <option value="Bracelets" <?= $update_product && $update_product['type'] == 'Bracelets' ? 'selected' : '' ?>>Bracelets</option>
            <option value="Anklets" <?= $update_product && $update_product['type'] == 'Anklets' ? 'selected' : '' ?>>Anklets</option>
            <option value="Ring" <?= $update_product && $update_product['type'] == 'Ring' ? 'selected' : '' ?>>Ring</option>
            <option value="Pendant" <?= $update_product && $update_product['type'] == 'Pendant' ? 'selected' : '' ?>>Pendant</option>
        </select>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="Men" <?= $update_product && $update_product['gender'] == 'Men' ? 'selected' : '' ?>>Men</option>
            <option value="Women" <?= $update_product && $update_product['gender'] == 'Women' ? 'selected' : '' ?>>Women</option>
        </select>

        <label>Price (in ₹):</label>
        <input type="number" name="price" value="<?= $update_product ? $update_product['price'] : '' ?>" step="0.01" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required><?= $update_product ? htmlspecialchars($update_product['description']) : '' ?></textarea>

        <label>Product Image:</label>
        <input type="file" name="image" <?= $update_product ? '' : 'required' ?> accept="image/*">

        <button type="submit" name="<?= $update_product ? 'update_product' : 'add_product' ?>">
            <?= $update_product ? 'Update Product' : 'Add Product' ?>
        </button>
    </form>

    <!-- Product Table -->
    <h2>Your Products</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Category</th>
            <th>Type</th>
            <th>Gender</th>
            <th>Price (₹)</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $products_result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['price']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><img src="<?= $row['image'] ?>" alt="Product Image" width="50"></td>
            <td><a href="?edit=<?= $row['id'] ?>">Edit</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
