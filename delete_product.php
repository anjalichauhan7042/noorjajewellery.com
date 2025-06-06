<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // First, get the image path to delete the file
    $query = "SELECT image FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Delete product if exists
    if ($product) {
        // Remove image file if it exists and is not empty
        if (!empty($product['image']) && file_exists($product['image'])) {
            unlink($product['image']);
        }

        // Delete product from database
        $delete_query = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "✅ Product deleted successfully.";
        } else {
            $_SESSION['message'] = "❌ Failed to delete product.";
        }
    } else {
        $_SESSION['message'] = "⚠️ Product not found.";
    }
} else {
    $_SESSION['message'] = "⚠️ Invalid product ID.";
}

// Redirect back to manage products page
header("Location: manage_products1.php");
exit();
?>
