<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        // Check if category already exists
        $query = "SELECT * FROM categories WHERE name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Category already exists!";
        } else {
            // Insert new category
            $insert_query = "INSERT INTO categories (name) VALUES (?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("s", $category_name);
            if ($stmt->execute()) {
                $message = "Category added successfully!";
            } else {
                $message = "Error adding category!";
            }
        }
    } else {
        $message = "Please enter a category name!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Manager Dashboard</title>
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
            color: #003366;
            text-align: center;
        }
        .message {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            display: block;
            width: 100%;
            background: #003366;
            color: white;
            padding: 10px;
            border: none;
            margin-top: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: #002244;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Category</h2>
    <?php if ($message) { echo "<p class='message'>$message</p>"; } ?>
    <form method="POST">
        <label for="category_name">Category Name:</label>
        <input type="text" name="category_name" id="category_name" required>
        <button type="submit">Add Category</button>
    </form>
  <a href="manager_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>


</div>
</body>
</html>
