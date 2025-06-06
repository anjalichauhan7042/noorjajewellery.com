<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $query = "SELECT * FROM categories WHERE id = $category_id";
    $result = mysqli_query($conn, $query);
    $category = mysqli_fetch_assoc($result);

    if (!$category) {
        echo "Category not found!";
        exit();
    }
} else {
    echo "Invalid category!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_category'])) {
    $new_category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    $update_query = "UPDATE categories SET name = '$new_category_name' WHERE id = $category_id";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['message'] = "Category updated successfully!";
        header("Location: add_category.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating category.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Category</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
    .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
    h2 { text-align: center; color: #003366; }
    input { padding: 10px; width: 100%; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
    button { background-color: green; color: white; padding: 10px; border: none; cursor: pointer; border-radius: 5px; }
  </style>
</head>
<body>

<div class="container">
    <h2>Edit Category</h2>
    <form method="post">
        <input type="text" name="category_name" value="<?php echo $category['name']; ?>" required>
        <button type="submit" name="update_category">Update Category</button>
    </form>
</div>

</body>
</html>
