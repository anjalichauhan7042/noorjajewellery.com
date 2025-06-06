<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$message = "";

// Fetch categories for dropdown
$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $category_id = $_POST['category'];
    $type = trim($_POST['type']);
    $gender = $_POST['gender'];
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    // Fetch the category name based on category_id
    $category_query = "SELECT name FROM categories WHERE id = ?";
    $stmt_category = $conn->prepare($category_query);
    $stmt_category->bind_param("i", $category_id);
    $stmt_category->execute();
    $category_result = $stmt_category->get_result();
    $category_row = $category_result->fetch_assoc();
    $category_name = $category_row['name'];

    if (!empty($name) && !empty($category_id) && !empty($type) && !empty($gender) && !empty($price) && !empty($description) && !empty($image)) 
    {
        $image_path = "images/products/" . basename($image);
        move_uploaded_file($image_tmp, $image_path);
        $insert_query = "INSERT INTO products (name, category_id, category, type, gender, price, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sissssss", $name, $category_id, $category_name, $type, $gender, $price, $description, $image_path);

        if ($stmt->execute()) {
            $message = "✅ Product added successfully!";
        } else {
            $message = "❌ Error adding product!";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #003366; margin-bottom: 20px; }
        label { font-weight: bold; margin-top: 10px; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        .btn-submit { background: #003366; color: white; border: none; padding: 10px; border-radius: 5px; font-size: 16px; cursor: pointer; margin-top: 15px; }
        .btn-submit:hover { background: #002244; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Product</h2>
    
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" required>

        <label for="category">Category:</label>
        <select name="category" required>
            <option value="">Select Category</option>
            <?php while ($row = mysqli_fetch_assoc($categories_result)) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php } ?>
        </select>

        <label for="type">Type:</label>
        <select name="type" required>
            <option value="">Select type</option>
            <option value="Pendant">Pendant</option>
            <option value="Rings">Rings</option>
            <option value="Ankletes">Ankletes</option>
            <option value="Earrings">Earrings</option>
            <option value="Bracelets">Bracelets</option>
        </select>

        <label for="gender">Gender:</label>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Men">Men</option>
            <option value="Women">Women</option>
        </select>

        <label for="price">Price (in ₹):</label>
        <input type="number" name="price" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <label for="image">Product Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" class="btn-submit">Add Product</button>
    </form>
    <a href="manager_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>