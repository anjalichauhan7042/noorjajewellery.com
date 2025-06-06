<?php
session_start();
include 'config.php';

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid product.";
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product details
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Noorja Jewelry</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); text-align: center; }
        .product-image { max-width: 100%; height: auto; border-radius: 10px; }
        .product-name { font-size: 24px; font-weight: bold; margin-top: 15px; }
        .product-price { font-size: 20px; color: #d61c4e; font-weight: bold; margin: 10px 0; }
        .product-description { font-size: 16px; margin: 10px 0; }
        .button-container { display: flex; flex-direction: column; gap: 10px; width: 100%; max-width: 500px; margin: auto; }
        .btn-group { display: flex; background-color: black; color: white; padding: 15px; justify-content: space-between; align-items: center; cursor: pointer; border: none; font-size: 16px; font-weight: bold; }
        .btn-group i { margin-right: 10px; }
        .btn-group:hover { background-color: #333; }
        .wishlist-btn { background: none; border: none; color: white; font-size: 20px; cursor: pointer; }
        .buy-now-btn { background-color: black; color: white; padding: 15px; width: 100%; text-align: center; font-size: 16px; font-weight: bold; cursor: pointer; border: none; }
        .buy-now-btn:hover { background-color: #333; }
.share-btn {
    background-color:black; /* Dark Blue */
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    display: block;
    width: 100%;
    text-align: center;
    margin-top: 10px;
}

.share-btn:hover {
    background-color: #003366; /* Slightly lighter blue */
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<div class="container">
    <!-- Product Image -->
    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
    
    <!-- Product Details -->
    <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
    <div class="product-price">₹<?php echo number_format($product['price'], 2); ?></div>
    <div class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></div>

    <!-- Buttons -->
   <!-- Add to Bag and Wishlist -->
<div class="btn-group">
    <a href="add_to_cart.php?id=<?php echo $product_id; ?>" style="text-decoration: none; color: white; width: 100%;">
        <span><i class="fa fa-shopping-bag"></i> ADD TO BAG →</span>
    </a>
    
    <a href="add_to_wishlist.php?id=<?php echo $product_id; ?>" class="wishlist-btn">
        <i class="fa fa-heart"></i>
    </a>
</div>
<div style="height: 15px;"></div> 

<!-- Buy Now -->
<div class="btn-group">
    <form action="cart1.php" method="POST" style="width: 100%; margin: 0;">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <button type="submit" style="background: none; border: none; color: white; width: 100%; text-align: center; font-size: 16px; font-weight: bold; cursor: pointer;">
            <i class="fa fa-bolt"></i> BUY IT NOW →
        </button>
    </form>
</div>

<!-- Share Button -->
<button class="share-btn" onclick="copyProductLink()">SHARE</button>

<!-- Hidden Input Field -->
<input type="text" id="productLink" value="<?php echo 'http://localhost/Noorja/product_details.php?id=' . $product_id; ?>" hidden>

<!-- JavaScript to Copy Link -->
<script>
function copyProductLink() {
    var link = document.getElementById("productLink");
    link.type = "text";  // Make it visible to copy
    link.select();
    document.execCommand("copy");
    link.type = "hidden";  // Hide it again

    // Show a temporary message
    alert("✅ Link Copied!");
}
</script>

</div>

</div>

</body>
</html>
