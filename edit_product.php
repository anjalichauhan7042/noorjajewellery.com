<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$message = "";
$id = $_GET['id'];

// Fetch product data
$product_query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Fetch categories
$categories_result = mysqli_query($conn, "SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $category_id = $_POST['category'];
    $type = trim($_POST['type']);
    $gender = $_POST['gender'];
    $price = $_POST['price'];
    $description = trim($_POST['description']);

    $image_path = $product['image']; // default to old image

    // Handle image upload if a new file is provided
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "images/products/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            $message = "❌ Image upload failed.";
        }
    }

    if (!empty($name) && !empty($category_id) && !empty($type) && !empty($gender) && !empty($price) && !empty($description)) {
        $update_query = "UPDATE products SET name=?, category_id=?, type=?, gender=?, price=?, description=?, image=? WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sissdssi", $name, $category_id, $type, $gender, $price, $description, $image_path, $id);

        if ($stmt->execute()) {
            $message = "✅ Product updated successfully!";
        } else {
            $message = "❌ Error updating product!";
        }
    } else {
        $message = "⚠️ All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #003366; }
        form { display: flex; flex-direction: column; }
        label { margin-top: 10px; font-weight: bold; }
        input, select, textarea { padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        button { margin-top: 20px; padding: 10px; background: #003366; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #002244; }
        .message { margin-top: 10px; padding: 10px; border-radius: 5px; background: #e7f3fe; color: #003366; }
        img { margin-top: 10px; max-width: 100px; border-radius: 5px; }
        a { text-decoration: none; color: #003366; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Product</h2>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label>Category:</label>
        <select name="category" required>
            <?php while ($cat = mysqli_fetch_assoc($categories_result)) { ?>
                <option value="<?php echo $cat['id']; ?>" <?php if ($product['category_id'] == $cat['id']) echo "selected"; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Type:</label>
        <input type="text" name="type" value="<?php echo htmlspecialchars($product['type']); ?>" required>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="Unisex" <?php if ($product['gender'] == "Unisex") echo "selected"; ?>>Unisex</option>
            <option value="Men" <?php if ($product['gender'] == "Men") echo "selected"; ?>>Men</option>
            <option value="Women" <?php if ($product['gender'] == "Women") echo "selected"; ?>>Women</option>
        </select>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

        <label>Current Image:</label>
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">

        <label>Change Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Update Product</button>
    </form>

    <a href="manage_products1.php">⬅ Back to Manage Products</a>
</div>

</body>
</html>
