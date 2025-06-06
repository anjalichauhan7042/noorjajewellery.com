<?php
session_start();
include 'config.php';

// Ensure only vendors can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: login.php");
    exit();
}

$vendor_id = $_SESSION['user_id']; // Get vendor ID from session
$product_id = $_GET['product_id']; // Get product ID from URL
$message = "";

// Fetch the product details
$query = "SELECT * FROM products WHERE id = ? AND vendor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $product_id, $vendor_id);
$stmt->execute();
$product_result = $stmt->get_result();

if ($product_result->num_rows == 0) {
    header("Location: vendor_dashboard.php");
    exit();
}

$product = $product_result->fetch_assoc();

// Handle product update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $gender = $_POST['gender'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = "images/products/" . $image_name;

    // Update product with the new details
    if ($image_name) {
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            $stmt = $conn->prepare("UPDATE products SET name = ?, category = ?, type = ?, gender = ?, price = ?, description = ?, image = ? WHERE id = ?");
            $stmt->bind_param("sssssssi", $name, $category, $type, $gender, $price, $description, $image_folder, $product_id);
        }
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, category = ?, type = ?, gender = ?, price = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $category, $type, $gender, $price, $description, $product_id);
    }

    if ($stmt->execute()) {
        $message = "Product updated successfully!";
    } else {
        $message = "Error updating product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product - Vendor Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #003366;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input, select, textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 15px;
            padding: 10px;
            background: #ff8c00;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: #ff6a00;
        }
        .message {
            text-align: center;
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Product</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Category:</label>
        <select name="category" required>
            <option value="Handmade" <?= $product['category'] == 'Handmade' ? 'selected' : '' ?>>Handmade</option>
        </select>

        <label>Type:</label>
        <select name="type" required>
            <option value="Earrings" <?= $product['type'] == 'Earrings' ? 'selected' : '' ?>>Earrings</option>
            <option value="Bracelets" <?= $product['type'] == 'Bracelets' ? 'selected' : '' ?>>Bracelets</option>
            <option value="Anklets" <?= $product['type'] == 'Anklets' ? 'selected' : '' ?>>Anklets</option>
            <option value="Ring" <?= $product['type'] == 'Ring' ? 'selected' : '' ?>>Ring</option>
            <option value="Pendant" <?= $product['type'] == 'Pendant' ? 'selected' : '' ?>>Pendant</option>
        </select>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="Men" <?= $product['gender'] == 'Men' ? 'selected' : '' ?>>Men</option>
            <option value="Women" <?= $product['gender'] == 'Women' ? 'selected' : '' ?>>Women</option>
        </select>

        <label>Price (in ₹):</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" step="0.01" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label>Product Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update_product">Update Product</button>
    </form>

    <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
</div>

</body>
</html>
